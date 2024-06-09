@extends('layouts.app')

@section('subtitle', 'Changements de Couches')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Changements de Couches')

@section('extra-css')
<style>
    .small-box {
    background-color: #f0f0f0;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    position: relative;
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
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: #176FA1;
        margin-right: 20px;
    }
    .change-details {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }
    .change-entry {
        position: relative; /* nécessaire pour positionner la croix */
        min-width: 150px;
        padding: 10px;
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

    .date-picker-container button {
        margin: 0 5px;
        padding: 5px 10px;
        font-size: 20px;
        border: none;
        color: #176FA1;
        background-color: #f4f6f9;
    }

    .date-picker-container input {
        text-align: center;
        font-size: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
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
    .large-icon {
        font-size: 2rem; /* Ajustez cette valeur pour obtenir la taille désirée */
        color: #176FA1; /* Ajustez la couleur selon vos besoins */
    }
    .change-time, .change-description {
        font-size: 1rem;
        color: #333;
    }

    .change-entry .delete-icon {
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

    <div id="diaper-change-container" class="row">
        <!-- Les boîtes seront ajoutées ici par JavaScript -->
    </div>

    <!-- Élément de chargement -->
    <div id="loading" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div>Chargement en cours...</div>
    </div>

    @include('worker.section.diaper_change_modal') <!-- Inclure le modal pour ajouter/modifier les changements de couches -->
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
            loadDiaperChangesForDate(dateStr);
        }
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadDiaperChangesForDate(datePickerElement.value);
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadDiaperChangesForDate(datePickerElement.value);
    });

    loadDiaperChangesForDate(datePickerElement.value);
});

async function getCsrfToken() {
    await fetch('/sanctum/csrf-cookie', {
        credentials: 'include' // Important pour envoyer les cookies
    });
}

async function loadDiaperChangesForDate(date) {
    document.getElementById('loading').style.display = 'flex'; // Afficher le loader
    const sectionId = '{{ Auth::user()->worker->currentSection->section->id }}';

    try {
        // Récupérer les données des enfants de la section
        const childrenResponse = await fetch(`/api/children/section/${sectionId}/date/${date}`, {
            credentials: 'include' ,
            headers: {
                'Accept': 'application/json'
            }
        });
        const childrenData = await childrenResponse.json();

        const diaperResponse = await fetch(`/api/diaper-changes/section/${sectionId}/date/${date}`, {
            credentials: 'include' ,
            headers: {
                'Accept': 'application/json'
            }
        });
        const diaperChanges = await diaperResponse.json();
        displayChildrenWithDiaperChanges(childrenData,diaperChanges );
        document.getElementById('loading').style.display = 'none'; // Masquer le loader en cas d'erreur


    } catch (error) {
        console.error('Error loading data:', error);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader en cas d'erreur
    }
}

function displayChildrenWithDiaperChanges(children, diaperChanges) {
    const container = document.getElementById('diaper-change-container');
    container.innerHTML = ''; // Effacer le contenu précédent

    children.forEach(child => {
        if (!child) return;
        const childDiaperChanges = diaperChanges.filter(change => change.child_id === child.id);

        let diaperHtml = childDiaperChanges.map(change => {
            const changeTime = new Date(change.happened_at).toLocaleTimeString();
            return `
                <div class="change-entry">
                    <i class="delete-icon fas fa-times-circle" onclick="deleteDiaperChange(${change.id})"></i>
                    <i class="fa-solid fa-${change.poop_consistency === 'watery' ? 'tint' : change.poop_consistency === 'soft' ? 'poop' : 'poo'} large-icon"></i>
                    <div class="change-time">${new Date(change.happened_at).toLocaleTimeString()}</div>
                    <div class="change-consistency">${change.poop_consistency === 'watery' ? 'Liquide' : change.poop_consistency === 'soft' ? 'Mou' : 'Normale'}</div>
                    <button class="btn btn-info btn-sm mt-2" onclick="openDiaperChangeModal(${child.id}, ${change.id})">Modifier</button>
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
                    <div class="change-details">${diaperHtml}</div>
                    <button class="btn btn-primary" onclick="openDiaperChangeModal(${child.id})"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });
}


async function submitDiaperChangeForm() {
    const childId = document.getElementById('childId').value;
    const diaperDate = document.getElementById('date-picker').value;
    const poopConsistency = document.getElementById('poop_consistency').value;
    const notes = document.getElementById('notes').value;
    const diaperTime = document.getElementById('diaper_time').value;
    const diaperChangeId = document.getElementById('diaperChangeId').value; // Assurez-vous que ce champ est défini dans votre formulaire
    const token = sessionStorage.getItem('authToken'); // Récupérer le token stocké dans la session

    const data = {
        child_id: childId,
        date: diaperDate,
        time: diaperTime,
        poop_consistency: poopConsistency,
        notes: notes
    };

    const url = diaperChangeId ? `/api/diaper-changes/${diaperChangeId}` : '/api/diaper-changes';
    const method = diaperChangeId ? 'PUT' : 'POST';

    console.log('Data:', data);
    try {
        const response = await fetch(url, {
            method: method,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });

        console.log('Response:', response);
        if (!response.ok) {
            throw new Error('Failed to save diaper change');
        }

        const result = await response.json();
        console.log('Diaper change saved successfully:', result);
        alert('Changement de couche enregistré avec succès');
        $('#diaperChangeModal').modal('hide');

        // Recharger les changements de couches pour la date actuelle
        loadDiaperChangesForDate(diaperDate);

    } catch (error) {
        console.error('Error saving diaper change:', error);
        alert('Erreur lors de l\'enregistrement du changement de couche');
    }
}

async function openDiaperChangeModal(childId, changeId = null) {
    console.log('Opening diaper change modal for child:', childId);

    document.getElementById('childId').value = childId;

    if (changeId) {
        try {
            const response = await fetch(`/api/diaper-changes/${changeId}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) {
                throw new Error('Failed to load diaper change details');
            }
            const data = await response.json();
            document.getElementById('diaperChangeId').value = data.id;
            document.getElementById('poop_consistency').value = data.poop_consistency;
            document.getElementById('notes').value = data.notes;
            document.getElementById('diaper_time').value = data.happened_at.substr(11, 5); // Suppose HH:mm format
        } catch (error) {
            console.error('Error loading diaper change details:', error);
            alert('Failed to load diaper change details.');
        }
    } else {
        document.getElementById('diaperChangeId').value = '';
        document.getElementById('poop_consistency').value = '';
        document.getElementById('notes').value = '';
        document.getElementById('diaper_time').value = '';
    }

    $('#diaperChangeModal').modal('show');
}




async function deleteDiaperChange(changeId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce changement de couche?')) return;
    const token = sessionStorage.getItem('authToken'); // Récupérer le token stocké
    try {
        const response = await fetch(`/api/diaper-changes/${changeId}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete diaper change');
        }

        const result = await response.json();
        console.log('Diaper change deleted successfully:', result);
        alert('Changement de couche supprimé avec succès');
        loadDiaperChangesForDate(document.getElementById('date-picker').value);

    } catch (error) {
        console.error('Error deleting diaper change:', error);
        alert('Erreur lors de la suppression du changement de couche');
    }
}

</script>
@endpush
