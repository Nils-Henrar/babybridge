@extends('layouts.app')

@section('subtitle', 'Repas des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Repas')

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
        color: #176FA1;
        margin-right: 20px;
    }

    .meal-details {
        display: flex;
        flex-wrap: wrap; /* Permet aux éléments de passer à la ligne si l'espace horizontal est insuffisant */
        justify-content: flex-start; /* Alignement horizontal */
        align-items: center; /* Alignement vertical */
        gap: 10px; /* Espacement entre les éléments */
    }

    .meal-entry {
        position: relative;
        min-width: 150px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        margin-right: 10px;
        text-align: center;
    }

    .meal-entry .delete-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        color: red;
        cursor: pointer;
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

    .meal-time {
    font-size: 1rem;
    color: #333;
    }

    .meal-quantity {
        font-size: 1rem;
        color: #333;
    }

    .large-icon {
        font-size: 2rem; /* Ajustez cette valeur pour obtenir la taille désirée */
        color: #176FA1; /* Ajustez la couleur selon vos besoins */
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
document.addEventListener("DOMContentLoaded", async function() {
    await getCsrfToken(); // Initialiser la protection CSRF

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

    loadChildrenAndMeals(datePickerElement.value); // Charge initialement les repas pour la date courante
    loadMealTypes(); // Appel pour charger les types de repas dans le formulaire modal
});

async function getCsrfToken() {
    await fetch('/sanctum/csrf-cookie', {
        credentials: 'include' // Important pour envoyer les cookies
    });
}

async function loadChildrenAndMeals(date) {
    document.getElementById('loading').style.display = 'flex'; // Afficher le loader
    let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}'; // ID de la section, assurez-vous qu'il est correctement défini

    try {
        // Récupérer les données des enfants de la section
        const childrenResponse = await fetch(`/api/children/section/${sectionId}/date/${date}`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
            }
        });
        const childrenData = await childrenResponse.json();

        // Récupérer les repas des enfants pour la section et la date spécifiées
        const mealsResponse = await fetch(`/api/meals/section/${sectionId}/date/${date}`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
            }
        });
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
    console.log('Children:', children);
    children.forEach(child => {
        if (!child) return;
        const flatMeals = meals.flat();
        const childMeals = flatMeals.filter(meal => meal.child_id === child.id);

        let mealsHtml = childMeals.map(meal => `
        <div class="meal-entry">
            <i class="delete-icon fas fa-times-circle" onclick="deleteMeal(${meal.id})"></i>
            <i class="fas fa-solid ${meal.meal.type === 'feeding bottle' ? 'fa-bottle-water' : meal.meal.type === 'fruit' ? 'fa-apple-alt' : 'fa-carrot'} large-icon"></i>
            <div class="meal-time">${new Date(meal.meal_time).toLocaleTimeString()}</div>
            <div class="meal-quantity">${meal.quantity} ${meal.meal.type === 'feeding bottle' ? 'ml' : ''}</div>
            <button class="btn btn-info btn-sm mt-2" onclick="openMealModal(${meal.id})">Modifier</button>
        </div>

        `).join('');
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

                        <div class="meal-details">
                            ${mealsHtml}
                        </div>

                        <div class="form-check">

                        <input class="form-check-input select-checkbox child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}">

                        </div>
                    </div>

                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });

    const addButtonHtml = `
        <div class="select-all-container">
            <button class="btn btn-primary" onclick="openMealModal()"><i class="fas fa-plus"></i></button>
            <button class="btn btn-secondary select-all-btn" onclick="selectAllChildren()">Select. tous</button>   
        </div>
    `;

    container.insertAdjacentHTML('afterbegin', addButtonHtml);
}

function loadMealTypes() {
    fetch('/api/meal-types', {
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
        }
    })
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
            
            // Traduire les types de repas spécifiques
            let translatedType;
            switch (meal.type) {
                case 'feeding bottle':
                    translatedType = 'Biberon';
                    break;
                case 'vegetable':
                    translatedType = 'Légume';
                    break;
                case 'fruit':
                    translatedType = 'Fruit';
                    break;
                default:
                    translatedType = meal.type; // Garde le type original si pas de traduction
            }
            
            option.textContent = translatedType;
            select.appendChild(option);
        });
    })
    .catch(error => console.error('Error loading meal types:', error));
}

async function submitMealForm() {
    const mealId = document.getElementById('mealId').value;
    const mealTime = document.getElementById('meal_time').value;
    const mealType = document.getElementById('meal_type').value;
    const quantity = document.getElementById('quantity').value;
    const notes = document.getElementById('notes').value;
    const mealDate = document.getElementById('date-picker').value;
    const selectedChildIds = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);

    const data = {
        child_ids: selectedChildIds,
        meal_id: mealType,
        meal_time: `${mealDate} ${mealTime}:00`,
        quantity: quantity,
        notes: notes
    };

    console.log('Meal data:', data);

    const url = mealId ? `/api/meals/${mealId}` : '/api/meals';
    const method = mealId ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
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

function selectAllChildren() {
    const isChecked = document.querySelector('.select-all-btn').innerText === 'Sélectionner tout';
    document.querySelectorAll('.child-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    document.querySelector('.select-all-btn').innerText = isChecked ? 'Désélectionner tout' : 'Sélectionner tout';
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

async function deleteMeal(mealId) {
    
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce repas?')) {
        return;
    }

    

    try {
        const response = await fetch(`/api/meals/${mealId}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete meal');
        }

        const result = await response.json();
        console.log('Meal deleted successfully:', result);
        console.log('Deleting meal with ID:', mealId);
        alert('Repas supprimé avec succès');

        // Recharger les repas pour la date actuelle
        loadChildrenAndMeals(document.getElementById('date-picker').value);
    } catch (error) {
        console.error('Error deleting meal:', error);
        alert('Erreur lors de la suppression du repas');
    }
}

async function openMealModal(mealId = null) {
    const modal = $('#mealModal');
    const form = document.getElementById('mealForm');

    if (!mealId) {
        const selectedChildIds = Array.from(document.querySelectorAll('.child-checkbox:checked')).map(checkbox => checkbox.value);
        if (selectedChildIds.length === 0) {
            alert('Veuillez sélectionner au moins un enfant pour ajouter un repas.');
            return;
        }

        form.reset(); // Réinitialiser le formulaire
        document.getElementById('mealId').value = '';
        document.getElementById('meal_time').value = '';
        document.getElementById('meal_type').value = '';
        document.getElementById('quantity').value = '';
        document.getElementById('notes').value = '';
        adjustQuantityInput(); // Appeler la fonction pour ajuster le champ de quantité
    } else {
        try {
            const response = await fetch(`/api/meals/${mealId}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load meal details');
            }

            const meal = await response.json();
            document.getElementById('mealId').value = meal.id;
            document.getElementById('meal_time').value = meal.meal_time.substr(11, 5);
            document.getElementById('meal_type').value = meal.meal_id;
            adjustQuantityInput(); // Appeler la fonction pour ajuster le champ de quantité en fonction du type de repas
            document.getElementById('quantity').value = meal.quantity;
            document.getElementById('notes').value = meal.notes;
        } catch (error) {
            console.error('Error loading meal details:', error);
            alert('Failed to load meal details');
            return;
        }
    }
    $('#mealModal').modal('show');
}
</script>

@endpush
