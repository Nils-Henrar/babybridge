@extends('layouts.app')

@section('subtitle', 'Repas des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Repas')

@section('extra-css')
<style>
    /* Styles généraux pour la page */
    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    /* Styles pour les sections de titre */
    .title-section {
        font-size: 2rem;
        font-weight: bold;
        color: #176FA1;
        margin-bottom: 30px;
        text-align: center;
    }

    /* Styles pour les boîtes d'information */
    .small-box {
        background-color: #D9D9D9;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 50px;
        color: #176FA1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Styles pour les entrées de date et de temps */
    .date-picker-container,
    .timepicker-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .date-picker-container button,
    .timepicker-container button {
        margin: 0 5px;
        padding: 5px 10px;
        font-size: 20px;
        background-color: #f8f9fa;
        border: none;
        color: #176FA1;
        cursor: pointer;
    }

    .date-picker-container input,
    .timepicker-container input {
        text-align: center;
        font-size: 20px;
        border-radius: 25px;
        padding: 8px 10px;
    }

    /* Styles pour les formulaires dans les modals */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-size: 1.5rem;
        color: #176FA1;
    }

    .form-group select{
        font-size: 1rem;
        border-radius: 25px;
        padding: 8px 10px;
    }

    .form-control {
        font-size: 1.2rem;
        border-radius: 25px;
        padding: 8px 10px;
    }

    /* Boutons pour enregistrer et fermer dans les modals */
    .btn-primary {
        background-color: #176FA1;
        border: none;
        padding: 10px 20px;
        font-size: 1.2rem;
        color: white;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background-color: #105078;
    }

    .fa-arrow-left,
    .fa-arrow-right {
        color: #176FA1; /* Couleur bleue pour les icônes de navigation */
    }

    /* Loader pendant le chargement des données */
    #loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
    }

    #loading div {
        font-size: 2rem;
        color: white;
    }

    .meal-details {
    display: flex;
    flex-wrap: wrap; /* Permet aux éléments de passer à la ligne si l'espace horizontal est insuffisant */
    justify-content: flex-start; /* Alignement horizontal */
    align-items: center; /* Alignement vertical */
    gap: 10px; /* Espacement entre les éléments */
    }

    .meal-entry {
        flex: 1;
        min-width: 150px; /* Minimum width for each meal entry */
        padding: 5px;
        background-color: #f0f0f0;
        border-radius: 10px;
        margin-right: 10px; /* Espacement entre les entrées */
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

    <div id="meal-container" class="row"></div> <!-- Container for meals -->


    @include('worker.section.meal_modal') <!-- Include the modal for adding meals -->

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
            console.log('Date after change in datePicker:', dateStr);
            loadChildrenAndMeals(dateStr); // Charge les données pour la date sélectionnée
        }
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadChildrenAndMeals(datePickerElement.value);
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadChildrenAndMeals(datePickerElement.value);
    });

    loadChildrenAndMeals(datePickerElement.value);// Charge initialement les repas pour la date courante
    loadMealTypes(); // Appel pour charger les types de repas dans le formulaire modal
});

async function loadChildrenAndMeals(date) {
    document.getElementById('loading').style.display = 'flex'; // Afficher le loader
    let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}'; // ID de la section, assurez-vous qu'il est correctement défini

    try {
        // Récupérer les données des enfants de la section
        const childrenResponse = await fetch(`/api/children/section/${sectionId}`);
        const childrenData = await childrenResponse.json();

        // Récupérer les repas des enfants pour la section et la date spécifiées
        const mealsResponse = await fetch(`/api/meals/section/${sectionId}/date/${date}`);
        const mealsData = await mealsResponse.json();
        displayChildrenWithMeals(childrenData, mealsData);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader
    } catch (error) {
        console.error('Error loading data:', error);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader en cas d'erreur
    }
}


function displayChildrenWithMeals(children, meals) {
    let container = document.getElementById('meal-container');
    container.innerHTML = ''; // Effacer le contenu précédent
    children.forEach(child => {
        const flatMeals = meals.flat();
        const childMeals = flatMeals.filter(meal => meal.child_id === child.id);

        let mealsHtml = childMeals.map(meal => `
        <div class="meal-entry">
            <strong>
                ${meal.meal.type === 'feeding bottle' ? '<i class="fa-solid fa-bottle-water"></i>' : 
                meal.meal.type === 'fruit' ? '<i class="fas fa-apple-alt"></i>' : 
                '<i class="fas fa-carrot"></i>'}
            </strong>: ${meal.quantity} ${ meal.meal.type === 'feeding bottle' ? 'ml' : '' }<p>${new Date(meal.meal_time).toLocaleTimeString()}</p>
        </div>
        `).join('');
        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${child.firstname} ${child.lastname}</h3>
                        <div class="meal-details">
                            ${mealsHtml}
                        </div>
                        <button class="btn btn-primary" onclick="openMealModal(${child.id})">Ajouter un repas</button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });
}

function loadMealTypes() {
    fetch('/api/meal-types')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('meal_type');
            select.innerHTML = ''; // Effacer les options existantes
            // "sélectionnez le type de repas" comme option par défaut
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionnez le type de repas';
            select.appendChild(defaultOption);
            data.forEach(meal => {
                const option = document.createElement('option');
                option.value = meal.id;
                option.textContent = meal.type;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading meal types:', error));
}


async function submitMealForm() {
    const mealTime = document.getElementById('meal_time').value;
    const mealType = document.getElementById('meal_type').value;
    const quantity = document.getElementById('quantity').value;
    const notes = document.getElementById('notes').value;
    const mealDate = document.getElementById('date-picker').value;
    const selectedChildId = document.getElementById('childIdInput').value;

    const data = {
        child_id: selectedChildId,
        meal_id: mealType,
        date: mealDate,
        time: mealTime,
        quantity: quantity,
        notes: notes
    };

    console.log('Meal data:', data);

    try {
        const response = await fetch('/api/meals', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Failed to save meal');
        }

        const result = await response.json();
        console.log('Meal saved successfully:', result);
        alert('Repas enregistré avec succès!');
        $('#mealModal').modal('hide'); // Fermer le modal après l'enregistrement

        // Rafraîchir les repas pour la date sélectionnée
        loadChildrenAndMeals(mealDate);
    } catch (error) {
        console.error('Error saving meal:', error);
        alert('Erreur lors de l\'enregistrement du repas.');
    }
}


function adjustQuantityInput() {
    const mealTypeSelect = document.getElementById('meal_type');
    const quantityGroup = document.getElementById('quantity_group');
    const mealType = mealTypeSelect.value;

    // Effacez le contenu précédent du groupe de quantité
    quantityGroup.innerHTML = '';

    // Vérifiez le type de repas sélectionné
    if (mealType === '3') { // 3 est l'ID du type de repas 'Feeding bottle'
        // Si le type de repas est 'Feeding bottle', utilisez un input pour les millilitres
        quantityGroup.innerHTML = `
            <label for="quantity">Quantité (ml)</label>
            <input type="number" class="form-control" id="quantity" placeholder="Entrez la quantité en ml" required onchange="updateNotesBasedOnQuantity()">        `;
    } else {
        // Pour les autres types, proposez des options prédéfinies
        quantityGroup.innerHTML = `
            <label for="quantity">Quantité</label>
            <select class="form-control" id="quantity" required onchange="updateNotesBasedOnQuantity()">
                <option value="">Sélectionnez la quantité</option>
                <option value="refused">Refus</option>
                <option value="quarter">1/4</option>
                <option value="half">1/2</option>
                <option value="full">Tout</option>
            </select>
        `;
    }
}

function updateNotesBasedOnQuantity() {
    const quantityInput = document.getElementById('quantity');
    const notesTextarea = document.getElementById('notes');
    const quantity = quantityInput.value;

    let note = '';
    switch (quantity) {
        case 'refused':
            note = "a refusé de manger";
            break;
        case 'quarter':
            note = "a mangé le 1/4 de son repas";
            break;
        case 'half':
            note = "a mangé la moitié de son repas";
            break;
        case 'full':
            note = "a mangé tout le repas";
            break;
        default:
            if (quantity === '0') {
                note = "a refusé le biberon";
            } else if (quantity > 0) {
                note = `a bu ${quantity} ml`;
            }
            break;
    }
    notesTextarea.value = note; // Met à jour le champ des notes
}




function openMealModal(childId) {
    console.log('Opening modal for child:', childId); // Vérifier que l'ID est correct et que la fonction est appelée
    document.getElementById('childIdInput').value = childId;
    $('#mealModal').modal('show');
}

</script>
@endpush
