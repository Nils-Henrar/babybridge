@extends('layouts.app')

@section('subtitle', 'Siestes des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Siestes')

@section('extra-css')
<style>
    .small-box {
        background-color: #f9f9f9;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .nap-details {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }
    .nap-entry {
        min-width: 150px;
        padding: 10px;
        background-color: #f0f0f0;
        border-radius: 10px;
        margin-right: 10px;
    }
    .title-section {
        font-size: 2rem;
        font-weight: bold;
        color: #176FA1;
        margin-bottom: 30px;
        text-align: center;
    }
    .date-picker-container {
        margin-top: 20px;
        text-align: center;
    }
    .btn-primary {
        background-color: #176FA1;
        border: none;
        padding: 10px 20px;
        font-size: 1.2rem;
        color: white;
        cursor: pointer;
    }
    .btn-primary:hover {
        background-color: #105078;
    }
</style>
@endsection

@section('content_body')
<div class="container">
    <div class="title-section">Section: {{ Auth::user()->worker->currentSection->section->name }}</div>
    
    <div class="date-picker-container">
        <button id="prev-day"><i class="fas fa-arrow-left"></i></button>
        <input type="text" id="date-picker" class="form-control" style="display: inline-block; width: auto;">
        <button id="next-day"><i class="fas fa-arrow-right"></i></button>
    </div>

    <div id="nap-container" class="row">
        <!-- Les boîtes seront ajoutées ici par JavaScript -->
    </div>

    @include('worker.section.nap_modal') <!-- Modal pour ajouter/modifier les siestes -->

    <div id="loading" style="display: none; justify-content: center; align-items: center; height: 100vh;">
        <div>Chargement en cours...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const datePickerElement = document.getElementById('date-picker');
    const datePicker = flatpickr(datePickerElement, {
        defaultDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            loadNapsForDate(dateStr);
        }
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadNapsForDate(datePickerElement.value);
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate + 1));
        datePicker.setDate(nextDate);
        loadNapsForDate(datePickerElement.value);
    });

   

    loadNapsForDate(datePickerElement.value); // Charge initialement les siestes pour la date actuelle
});

async function selectAllChildren() {
    const isChecked = document.getElementById('selectAllChildren').checked;
    document.querySelectorAll('.child-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
    });

    //combien d'enfants sont sélectionnés
    console.log('Nombre d\'enfants sélectionnés:', document.querySelectorAll('.child-checkbox:checked').length);
}


async function loadNapsForDate(date) {
    document.getElementById('loading').style.display = 'flex'; // Affiche le loader
    let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}' // ID de la section
    try {
        const childrenResponse = await fetch(`/api/children/section/${sectionId}`);
        const childrenData = await childrenResponse.json();
        const napsResponse = await fetch(`/api/naps/section/${sectionId}/date/${date}`);
        const napsData = await napsResponse.json();

        console.log('Children:', childrenData);
        console.log('Naps:', napsData);
        displayChildrenWithNaps(childrenData, napsData);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader en cas d'erreur
    }
}

function displayChildrenWithNaps(children, naps) {
    const container = document.getElementById('nap-container');
    container.innerHTML = '';
    children.forEach(child => {
        if (!child) return;
        const childNaps = naps.filter(nap => nap.child_id === child.id);
        let napHtml = childNaps.map(nap => {
            return `
                <div class="nap-entry">
                    <div><strong>Début:</strong> ${nap.started_at}</div>

                    <div><strong>Durée:</strong> ${nap.ended_at ? `${Math.floor((new Date(nap.ended_at) - new Date(nap.started_at)) / 60000 / 60)}h ${Math.floor((new Date(nap.ended_at) - new Date(nap.started_at)) / 60000 % 60)}m` : 'En cours'}</div>
                    <div><strong>Qualité:</strong> ${nap.quality === 'bad' ? '<i class="fas fa-frown"></i>' : nap.quality === 'average' ? '<i class="fas fa-meh"></i>' : '<i class="fas fa-smile"></i>'}</div>
                    <div><strong>Notes:</strong> ${nap.notes || ''}</div>
                    <button class="btn btn-info btn-sm" onclick="openNapModal(${nap.id})">Modifier</button>
                </div>
            `;
        }).join('');
        
        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${child.firstname} ${child.lastname}</h3>
                        <div class="nap-details">${napHtml}</div>
                        <div class="form-check">
                            <input class="form-check-input child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}">
                            <label class="form-check-label" for="child-${child.id}">
                                Sélectionner pour la sieste
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });

    // Ajouter un bouton pour ouvrir le modal après avoir sélectionné les enfants
    const addButtonHtml = `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="selectAllChildren" onchange="selectAllChildren()">
            <label class="form-check-label" for="selectAllChildren">
                Sélectionner tous les enfants
            </label>
        </div>
        <button class="btn btn-primary my-3" onclick="openNapModal()">Ajouter une sieste</button>
    `;

    // Ajouter le bouton au début du conteneur
    container.insertAdjacentHTML('afterbegin', addButtonHtml);
}

async function openNapModal(napId = null) {
    const modal = $('#napModal');
    const form = document.getElementById('napForm');
    // Si aucun napId n'est fourni, considérez qu'il s'agit d'une nouvelle sieste
    if (!napId) {
        // Vérifier si au moins un enfant est sélectionné lorsque l'on ouvre pour une nouvelle sieste
        const selectedChildren = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        if (selectedChildren.length === 0) {
            alert('Veuillez sélectionner au moins un enfant pour la sieste.');
            return; // Ne pas ouvrir le modal si aucun enfant n'est sélectionné
        }
        // Réinitialisation du formulaire pour une nouvelle entrée
        form.reset();
        document.getElementById('napId').value = ''; 
    } else {
        // Chargement des données existantes pour la mise à jour
        try {
            const response = await fetch(`/api/naps/${napId}`);
            if (!response.ok) {
                throw new Error('Failed to load nap details');
            }
            const data = await response.json();
            document.getElementById('napId').value = data.id;
            document.getElementById('started_at').value = data.started_at.substr(11, 5); // Suppose HH:mm format
            document.getElementById('ended_at').value = data.ended_at ? data.ended_at.substr(11, 5) : '';
            document.getElementById('quality').value = data.quality;
            document.getElementById('notes').value = data.notes;
        } catch (error) {
            console.error('Error loading nap details:', error);
            alert('Failed to load nap details.');
            return; // Ne pas ouvrir le modal en cas d'échec du chargement des détails
        }
    }
    modal.modal('show');
}

async function submitNapForm() {
    const form = document.getElementById('napForm');
    const napId = document.getElementById('napId').value; //  existe dans votre formulaire?
    const isUpdating = !!napId; // Convertit napId en booléen, true si une mise à jour, false si une création

    // Préparation de l'URL et de la méthode
    const url = isUpdating ? `/api/naps/${napId}` : '/api/naps';
    const method = isUpdating ? 'PUT' : 'POST';

    // Heure de début et de fin
    const startedAt = document.getElementById('started_at').value;
    const endedAt = document.getElementById('ended_at').value;
    // Création de l'objet de données
    let data = {
        started_at: `${document.getElementById('date-picker').value} ${startedAt}:00`, // Combinez date et heure
        ended_at: endedAt ? `${document.getElementById('date-picker').value} ${endedAt}:00` : null,
        quality: document.getElementById('quality').value,
        notes: document.getElementById('notes').value,
    };


    if (isUpdating) {
        data.nap_id = napId; // Ajout de nap_id seulement pour la mise à jour
    } else {
        // Collecte des enfants sélectionnés pour la sieste
        const selectedChildren = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        data.child_ids = selectedChildren;
    }

    // Converti l'objet de données en JSON
    const jsonData = JSON.stringify(data);

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: jsonData
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error ${response.status}: ${errorText}`);
        }

        const result = await response.json();
        console.log('Nap saved successfully:', result);
        alert('Sieste enregistrée/modifiée avec succès!');
        $('#napModal').modal('hide');
        // Recharge ou actualise les données sur la page
        loadNapsForDate(document.getElementById('date-picker').value);
    } catch (error) {
        console.error('Error submitting nap form:', error);
        alert('Erreur lors de l\'enregistrement/modification de la sieste');
    }
    }


</script>
@endpush
