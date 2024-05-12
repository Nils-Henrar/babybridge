@extends('layouts.app')

@section('subtitle', 'Changements de Couches')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Changements de Couches')

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
    .change-details {
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

    .change-details {
    display: flex;
    flex-wrap: wrap; /* Permet aux éléments de passer à la ligne si l'espace horizontal est insuffisant */
    justify-content: flex-start; /* Alignement horizontal */
    align-items: center; /* Alignement vertical */
    gap: 10px; /* Espacement entre les éléments */
    }

    .change-entry {
        
        min-width: 150px; /* Minimum width for each meal entry */
        padding: 50px;
        background-color: #f0f0f0;
        border-radius: 10px;
        margin-right: 10px; /* Espacement entre les entrées */
        
    }
</style>
@endsection

@section('content_body')
<div class="container">
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
document.addEventListener("DOMContentLoaded", function() {
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

async function loadDiaperChangesForDate(date) {
    document.getElementById('loading').style.display = 'flex'; // Afficher le loader
    const sectionId = '{{ Auth::user()->worker->currentSection->section->id }}';

    try {
        // Récupérer les données des enfants de la section
        const childrenResponse = await fetch(`/api/children/section/${sectionId}`);
        const childrenData = await childrenResponse.json();

        const diaperResponse = await fetch(`/api/diaper-changes/section/${sectionId}/date/${date}`);
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
        const childDiaperChanges = diaperChanges.filter(change => change.child_id === child.id);

        let diaperHtml = childDiaperChanges.map(change => {
            return `
                <div class="change-entry" onclick="openDiaperChangeModal(${child.id})">
                    <strong>
                        ${change.poop_consistency === 'watery' ? '<i class="fas fa-tint"></i><span>liquide</span>' : 
                        change.poop_consistency === 'soft' ? '<i class="fa-solid fa-poop"></i><span>mou</span>' : '<i class="fa-solid fa-poo"></i><span>normale</span>'}
                    </strong>
                    <p>${new Date(change.happened_at).toLocaleTimeString()}</p>
                </div>
                
            `;
        }).join('');
        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${child.firstname} ${child.lastname}</h3>
                        <div class="change-details">
                            ${diaperHtml}
                        </div>
                        <button class="btn btn-primary" onclick="openDiaperChangeModal(${child.id})">Ajouter un change</button>
                    </div>
                </div>
            </div>
        `;

        container.innerHTML += boxHtml;
    });
}

async function submitDiaperChangeForm() {
    const childId = document.getElementById('childIdInput').value;
    const diaperDate = document.getElementById('date-picker').value;
    const poopConsistency = document.getElementById('poop_consistency').value;
    const notes = document.getElementById('notes').value;
    const diaperTime = document.getElementById('diaper_time').value;

    const data = {
        child_id: childId,
        date: diaperDate,
        time: diaperTime,
        poop_consistency: poopConsistency,
        notes: notes
    };

    console.log('Data:', data);
    try {
        const response = await fetch('/api/diaper-changes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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



function openDiaperChangeModal(childId) {
    // Initialiser le formulaire dans le modal ici
    console.log('Opening diaper change modal for child:', childId);
    $('#diaperChangeModal').modal('show');
    document.getElementById('childIdInput').value = childId;

}
</script>
@endpush
