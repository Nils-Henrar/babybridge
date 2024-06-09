@extends('layouts.app')

@section('subtitle', 'Activités des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Activités')

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
    padding-right: 50px;
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

    .activity-details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    }

    .activity-entry {
    position: relative;
    min-width: 150px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 10px;
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

    .btn-secondary {

    background-color: #666;
    border: none;
    padding: 10px 20px;
    font-size: 1.2rem;
    color: white;
    cursor: pointer;

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

    .activity-icon {
    font-size: 2rem;
    color: #176FA1;
    margin-bottom: 5px;
    }

    .activity-time, .activity-description {
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

    .activity-entry .delete-icon {
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

    <div id="activity-container" class="row"></div> <!-- Container for activities -->

    @include('worker.section.activity_modal') <!-- Modal pour ajouter une activité -->

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
            loadChildrenAndActivities(dateStr);
        }
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadChildrenAndActivities(datePickerElement.value);
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadChildrenAndActivities(datePickerElement.value);
    });

    loadChildrenAndActivities(datePickerElement.value);
    loadActivityTypes();
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

async function loadChildrenAndActivities(date) {
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
        const activitiesResponse = await fetch(`/api/activities/section/${sectionId}/date/${date}`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
            }
        });
        const activitiesData = await activitiesResponse.json();
        displayChildrenWithActivities(childrenData, activitiesData);
        document.getElementById('loading').style.display = 'none';
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loading').style.display = 'none';
    }
}

function displayChildrenWithActivities(children, activities) {
    const container = document.getElementById('activity-container');
    container.innerHTML = '';
    children.forEach(child => {
        if (!child) return;
        const childActivities = activities.filter(activity => activity.child_id === child.id);
        let activityHtml = childActivities.map(activity => {
            const activityTime = new Date(activity.performed_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
            return `
                <div class="activity-entry">
                    <i class="delete-icon fas fa-times-circle" onclick="deleteActivity(${activity.id})"></i>
                    <i class="fa-solid fa-puzzle-piece activity-icon"></i>
                    <div class="activity-time">${activityTime}</div>
                    <div class="activity-description">${activity.activity.description}</div>
                    <button class="btn btn-info btn-sm mt-2" onclick="openActivityModal(${activity.id}, ${activity.activity_id})">Modifier</button>
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
                        <p>${child.lastname}</p>
                    </div>
                    <div class="activity-details">${activityHtml}</div>
                    <div class="form-check">
                        <input class="form-check-input select-checkbox child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}">
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });

    const addButtonHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary my-3" onclick="openActivityModal()"><i class="fas fa-plus"></i></button>
            <button class="btn btn-secondary select-all-btn" onclick="selectAllChildren()">Select. tous</button>
        </div>
    `;

    container.insertAdjacentHTML('afterbegin', addButtonHtml);
}

async function openActivityModal(activityId = null) {
    const modal = $('#activityModal');
    const form = document.getElementById('activityForm');
    const activityIdInput = document.getElementById('activityId');
    const activityDescriptionInput = document.getElementById('activity_description');
    const activityTimeInput = document.getElementById('activity_time');
    
    if (!activityId) {
        const selectedChildren = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        if (selectedChildren.length === 0) {
            alert('Veuillez sélectionner au moins un enfant pour l\'activité.');
            return;
        }
        form.reset();
        if (activityIdInput) activityIdInput.value = ''; 
        if (activityDescriptionInput) activityDescriptionInput.value = '';
        if (activityTimeInput) activityTimeInput.value = '';
    } else {
        try {
            const response = await fetch(`/api/activities/${activityId}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                }
            });
            if (!response.ok) {
                throw new Error('Failed to load activity details');
            }
            const data = await response.json();
            if (activityIdInput) activityIdInput.value = data.id;
            if (activityDescriptionInput) activityDescriptionInput.value = data.activity_id;
            if (activityTimeInput) activityTimeInput.value = new Date(data.performed_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
        } catch (error) {
            console.error('Error loading activity details:', error);
            alert('Failed to load activity details.');
            return;
        }
    }
    modal.modal('show');
}

async function submitActivityForm() {
    const form = document.getElementById('activityForm');
    const activityId = document.getElementById('activityId').value;
    const isUpdating = !!activityId;

    const url = isUpdating ? `/api/activities/${activityId}` : '/api/activities';
    const method = isUpdating ? 'PUT' : 'POST';

    const description = document.getElementById('activity_description').value;
    const time = document.getElementById('activity_time').value;
    const date = document.getElementById('date-picker').value;

    let data = {
        performed_at: `${date} ${time}:00`,
        activity_id: description
    };

    if (!isUpdating) {
        const selectedChildren = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        data.child_ids = selectedChildren;
    }

    const jsonData = JSON.stringify(data);

    console.log('Submitting activity form:', jsonData);

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
        console.log('Activity saved successfully:', result);
        alert('Activité enregistrée/modifiée avec succès!');
        $('#activityModal').modal('hide');
        loadChildrenAndActivities(date);
    } catch (error) {
        console.error('Error submitting activity form:', error);
        alert('Erreur lors de l\'enregistrement/modification de l\'activité.');
    }
}

async function deleteActivity(activityId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette activité?')) return;

    try {
        const response = await fetch(`/api/activities/${activityId}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete activity');
        }

        const result = await response.json();
        console.log('Activity deleted successfully:', result);
        alert('Activité supprimée avec succès');
        loadChildrenAndActivities(document.getElementById('date-picker').value);
    } catch (error) {
        console.error('Error deleting activity:', error);
        alert('Erreur lors de la suppression de l\'activité');
    }
}

function loadActivityTypes() {
    fetch('/api/activity-types', {
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const selectElement = document.getElementById('activity_description');
        selectElement.innerHTML = '<option value="">Sélectionnez le type d\'activité</option>';
        data.forEach(activityType => {
            const option = document.createElement('option');
            option.value = activityType.id;
            option.textContent = activityType.description;
            selectElement.appendChild(option);
        });
    })
    .catch(error => console.error('Error loading activity types:', error));
}
</script>

@endpush
