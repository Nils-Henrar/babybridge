@extends('layouts.app')

@section('subtitle', 'Siestes des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Siestes')

@section('extra-css')
<style>
    .small-box {
        position: relative;
        background-color: #f0f0f0;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
    }

    .child-photo {
        flex-shrink: 0;
        margin-right: 15px;
    }

    .child-photo img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
    }

    .child-info {
        margin-right: 20px;
        color: #176FA1;
    }

    .nap-details {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .nap-entry {
        position: relative;
        min-width: 150px;
        padding: 20px; 
        background-color: #f9f9f9;
        border-radius: 10px;
        margin-right: 10px;
        text-align: center;
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

    .btn-secondary {

    background-color: #888;
    border: none;
    padding: 10px 20px;
    font-size: 1.2rem;
    color: white;
    cursor: pointer;

    }

    .btn-primary:hover {
        background-color: #105078;
    }

    .nap-icon {
        font-size: 2rem;
        color: #176FA1;
        margin-bottom: 5px;
    }

    .nap-time, .nap-duration {
        font-size: 1rem;
        color: #333;
    }

    .select-checkbox {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
        transform: scale(1.5);
        accent-color: #176FA1;
    }

    .select-all-btn {
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        margin-left: 20px;
        
    }

    .select-all-btn:hover {
        /* gris sombre */
        background-color: #666;
    }

    .select-all-container {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 15px;
        margin-right: 20px;

    }

    /* Assurez-vous que .form-check dans la small-box est positionné correctement */
    .small-box .form-check {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    /* Assurez-vous que les éléments dans .small-box n'ont pas de marges excessives */
    .small-box .form-check-input {
        margin: 0;
    }
    
    .nap-entry .delete-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        color: red;
        cursor: pointer;
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
document.addEventListener("DOMContentLoaded", async function() {
    await getCsrfToken(); // Initialiser la protection CSRF

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
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadNapsForDate(datePickerElement.value);
    });

    loadNapsForDate(datePickerElement.value);
});

async function getCsrfToken() {
    await fetch('/sanctum/csrf-cookie', {
        credentials: 'include' // Important pour envoyer les cookies
    });
}

async function selectAllChildren() {
    const checkboxes = document.querySelectorAll('.child-checkbox');
    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });

    console.log('Nombre d\'enfants sélectionnés:', document.querySelectorAll('.child-checkbox:checked').length);
}

async function loadNapsForDate(date) {
    document.getElementById('loading').style.display = 'flex';
    let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}';
    try {
        const childrenResponse = await fetch(`/api/children/section/${sectionId}/date/${date}`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
            }
        });
        const childrenData = await childrenResponse.json();

        const napsResponse = await fetch(`/api/naps/section/${sectionId}/date/${date}`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
            }
        });
        const napsData = await napsResponse.json();
        displayChildrenWithNaps(childrenData, napsData);
        document.getElementById('loading').style.display = 'none';
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loading').style.display = 'none';
    }
}

function displayChildrenWithNaps(children, naps) {
    const container = document.getElementById('nap-container');
    container.innerHTML = '';
    children.forEach(child => {
        if (!child) return;
        const childNaps = naps.filter(nap => nap.child_id === child.id);
        let napHtml = childNaps.map(nap => {
            const startTime = nap.started_at.substr(11, 5);
            const duration = nap.ended_at ? `${Math.floor((new Date(nap.ended_at) - new Date(nap.started_at)) / 60000 / 60)}h ${Math.floor((new Date(nap.ended_at) - new Date(nap.started_at)) / 60000 % 60)}m` : 'En cours';
            return `
                <div class="nap-entry">
                    <i class="delete-icon fas fa-times-circle" onclick="deleteNap(${nap.id})"></i>
                    <i class="fas fa-solid fa-cloud-moon nap-icon"></i>
                    <div class="nap-time">${startTime}</div>
                    <div class="nap-duration">${duration}</div>
                    <button class="btn btn-info btn-sm mt-2" onclick="openNapModal(${nap.id})">Modifier</button>
                </div>
            `;
        }).join('');
        
        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="child-photo">
                        <img src="{{ asset('storage/${child.photo_path}') }}" alt="Photo de profil de ${child.firstname}">
                    </div>
                    <div class="child-info">
                        <h3>${child.firstname}</h3>
                        <p> ${child.lastname} </p>
                    </div>
                    <div class="nap-details">${napHtml}</div>
                    <div class="form-check">
                        <input class="form-check-input select-checkbox child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}">
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });

    const addButtonHtml = `
        <div class="select-all-container">
            <button class="btn btn-primary" onclick="openNapModal()"><i class="fas fa-plus"></i></button>
            <button class="btn btn-secondary select-all-btn" onclick="selectAllChildren()">Select. tous</button>   
        </div>
    `;

    container.insertAdjacentHTML('afterbegin', addButtonHtml);
}


async function openNapModal(napId = null) {
    const modal = $('#napModal');
    const form = document.getElementById('napForm');
    if (!napId) {
        const selectedChildren = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        if (selectedChildren.length === 0) {
            alert('Veuillez sélectionner au moins un enfant pour la sieste.');
            return;
        }
        form.reset();
        document.getElementById('napId').value = ''; 
    } else {
        try {
            const response = await fetch(`/api/naps/${napId}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                }
            });
            if (!response.ok) {
                throw new Error('Failed to load nap details');
            }
            const data = await response.json();
            document.getElementById('napId').value = data.id;
            document.getElementById('started_at').value = data.started_at.substr(11, 5);
            document.getElementById('ended_at').value = data.ended_at ? data.ended_at.substr(11, 5) : '';
            document.getElementById('quality').value = data.quality;
            document.getElementById('notes').value = data.notes;
        } catch (error) {
            console.error('Error loading nap details:', error);
            alert('Failed to load nap details.');
            return;
        }
    }
    modal.modal('show');
}

async function submitNapForm() {
    const form = document.getElementById('napForm');
    const napId = document.getElementById('napId').value;
    const isUpdating = !!napId;

    const url = isUpdating ? `/api/naps/${napId}` : '/api/naps';
    const method = isUpdating ? 'PUT' : 'POST';

    const startedAt = document.getElementById('started_at').value;
    const endedAt = document.getElementById('ended_at').value;

    let data = {
        started_at: `${document.getElementById('date-picker').value} ${startedAt}:00`,
        ended_at: endedAt ? `${document.getElementById('date-picker').value} ${endedAt}:00` : null,
        quality: document.getElementById('quality').value,
        notes: document.getElementById('notes').value,
    };

    if (!isUpdating) {
        const selectedChildren = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        data.child_ids = selectedChildren;
    }

    const jsonData = JSON.stringify(data);

    try {
        const response = await fetch(url, {
            method: method,
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
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
        loadNapsForDate(document.getElementById('date-picker').value);
    } catch (error) {
        console.error('Error submitting nap form:', error);
        alert('Erreur lors de l\'enregistrement/modification de la sieste');
    }
}

async function deleteNap(napId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette sieste?')) {
        return;
    }

    try {
        const response = await fetch(`/api/naps/${napId}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete nap');
        }

        const result = await response.json();
        console.log('Nap deleted successfully:', result);
        alert('Sieste supprimée avec succès');
        
        // Recharger les siestes pour la date actuelle
        loadNapsForDate(document.getElementById('date-picker').value);
    } catch (error) {
        console.error('Error deleting nap:', error);
        alert('Erreur lors de la suppression de la sieste');
    }
}
</script>


@endpush
