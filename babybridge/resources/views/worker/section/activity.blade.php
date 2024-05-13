@extends('layouts.app')

@section('subtitle', 'Activités des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Activités')

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
    .activity-details {
        margin: 0 15px;
    }
    .btn-primary {
        margin-top: 10px;
    }
    .date-picker-container {
        margin-top: 20px;
        text-align: center;
    }
    .fas {
        color: #176FA1;
    }

    .activity-details {
        display: flex;
        flex-wrap: wrap; /* Permet aux éléments de passer à la ligne si l'espace horizontal est insuffisant */
        justify-content: flex-start; /* Alignement horizontal */
        align-items: center; /* Alignement vertical */
        gap: 10px; /* Espacement entre les éléments */
    }

    .activity-entry {
        
        min-width: 150px; /* Minimum width for each meal entry */
        padding: 50px;
        background-color: #f0f0f0;
        border-radius: 10px;
        margin-right: 10px; /* Espacement entre les entrées */
        
    }

    .title-section {
        font-size: 2rem;
        font-weight: bold;
        color: #176FA1;
        margin-bottom: 30px;
        text-align: center;
    }

</style>
@endsection

@section('content_body')
<div class="container">
    <div class="title-section">Section: {{ Auth::user()->worker->currentSection->section->name }}</div>

    <div class="date-picker-container" style="text-align: center; margin-top: 20px;">
        <button id="prev-day"><i class="fas fa-arrow-left"></i></button>
        <input type="text" id="date-picker" class="form-control" style="display: inline-block; width: auto;">
        <button id="next-day"><i class="fas fa-arrow-right"></i></button>
    </div>

    <!-- Bouton pour ouvrir le modal d'ajout d'activité pour tous les enfants -->
<button class="btn btn-info" onclick="openActivityModalForAll()">Ajouter une activité pour tous</button>


    <div id="activity-container" class="row"></div> <!-- Container for activities -->

    @include('worker.section.activity_modal') <!-- Modal pour ajouter une activité -->

    @include('worker.section.activity_modal_for_all') <!-- Modal pour ajouter une activité pour tous les enfants -->

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
            loadChildrenAndActivities(dateStr); // Load activities for the selected date
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

    loadChildrenAndActivities(datePickerElement.value); // Initially load activities for the current date
    loadActivityTypes(); // Load activity types for the select input
});

async function loadChildrenAndActivities(date) {
    document.getElementById('loading').style.display = 'flex'; // Show the loader
    let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}'; // Section ID, make sure it is correctly set
    try {
        const childrenResponse = await fetch(`/api/children/section/${sectionId}/date/${date}`);
        if (!childrenResponse.ok) {
            throw new Error('HTTP error, status = ' + childrenResponse.status);
        }
        const childrenData = await childrenResponse.json();
        console.log('Children data:', childrenData);
        const activitiesResponse = await fetch(`/api/activities/section/${sectionId}/date/${date}`);
        const activitiesData = await activitiesResponse.json();
        displayChildrenWithActivities(childrenData, activitiesData);
        document.getElementById('loading').style.display = 'none'; // Hide the loader
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loading').style.display = 'none'; // Hide the loader in case of error
    }
}

function displayChildrenWithActivities(children, activities) {
    const container = document.getElementById('activity-container');
    container.innerHTML = ''; // Clear the container

    children.forEach(child => {
        if (!child) return; 

        const flatActivities = activities.flat();
        const childActivities = flatActivities.filter(activity => activity.child_id === child.id);

        let activitiesHtml = childActivities.map(activity => {
            if (!activity || !activity.activity) {
                return `<div class="activity-entry"><strong>No activity recorded</strong></div>`;
            }
            return `
                <div class="activity-entry">
                    <strong>${activity.activity.description}</strong>:
                    <p>${new Date(activity.performed_at).toLocaleTimeString()}</p>
                </div>
            `;
        }).join('');

        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${child.firstname} ${child.lastname}</h3>
                        <div class="activity-details">${activitiesHtml}</div>
                        <button class="btn btn-primary" onclick="openActivityModal(${child.id}, '', '')">Ajouter une activité</button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });
}


function loadActivityTypes() {
    fetch('/api/activity-types')
        .then(response => response.json())
        .then(data => {
            // Sélecteur pour un enfant spécifique
            const selectElementIndividual = document.getElementById('activity_description');
            // Sélecteur pour tous les enfants
            const selectElementAll = document.getElementById('activity_description_all');

            // Réinitialise les sélections
            selectElementIndividual.innerHTML = '<option value="">Sélectionnez le type d\'activité</option>';
            selectElementAll.innerHTML = '<option value="">Sélectionnez le type d\'activité</option>';

            // Ajoute des options aux deux sélecteurs
            data.forEach(activityType => {
                const optionIndividual = document.createElement('option');
                const optionAll = document.createElement('option');
                optionIndividual.value = activityType.id;
                optionIndividual.textContent = activityType.description;
                optionAll.value = activityType.id;
                optionAll.textContent = activityType.description;

                selectElementIndividual.appendChild(optionIndividual);
                selectElementAll.appendChild(optionAll);
            });
        })
        .catch(error => console.error('Error loading activity types:', error));
}

        

function openActivityModal(childId, description, time) {
    document.getElementById('childIdInput').value = childId;
    document.getElementById('activity_description').value = description;
    document.getElementById('activity_time').value = time;
    $('#activityModal').modal('show');
}

document.getElementById('activityForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const childId = document.getElementById('childIdInput').value;
    const description = document.getElementById('activity_description').value;
    const time = document.getElementById('activity_time').value;
    const date = document.getElementById('date-picker').value;

    const data = {
        child_id: childId,
        activity_id: description,
        time: time,
        date: date
    };
     console.log('Data:', data);
    try {
        const response = await fetch('/api/activities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Failed to save activity');
        }
        const result = await response.json();
        console.log('Activity saved successfully:', result);
        alert('Activity saved successfully!');
        $('#activityModal').modal('hide');
        loadChildrenAndActivities(date); // Reload activities for the selected date
    } catch (error) {
        console.error('Error saving activity:', error);
        alert('Error saving activity.');
    }
});


function openActivityModalForAll() {
    $('#activityModalForAll').modal('show');
}

document.getElementById('activityFormForAll').addEventListener('submit', async function(event) {
    event.preventDefault();
    const sectionId = '{{ Auth::user()->worker->currentSection->section->id }}'; // Assure-toi que cette valeur est correctement injectée.
    const description = document.getElementById('activity_description_all').value;
    const time = document.getElementById('activity_time_all').value;
    const date = document.getElementById('date-picker').value;

    const data = {
        activity_id: description,
        time: time,
        date: date
    };

    try {
        const response = await fetch(`/api/activities/section/${sectionId}/date/${date}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Failed to save activity for all children');
        }
        const result = await response.json();
        console.log('Activity saved successfully for all children:', result);
        alert('Activité enregistrée avec succès pour tous les enfants!');
        $('#activityModalForAll').modal('hide');
        loadChildrenAndActivities(date); // Reload activities
    } catch (error) {
        console.error('Error saving activity for all children:', error);
        alert('Erreur lors de l\'enregistrement de l\'activité.');
    }
});

</script>


@endpush
