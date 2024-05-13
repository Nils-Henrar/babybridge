@extends('layouts.app')

@section('subtitle', 'Photos des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Photos')

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
    .photo-details {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }
    .photo-entry {
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

    <div id="photo-container" class="row">
        <!-- Les boîtes seront ajoutées ici par JavaScript -->
    </div>

    @include('worker.section.photo_modal') <!-- Modal pour ajouter/modifier les photos -->

    <div id="loading" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
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
            loadPhotosForDate(dateStr);
        }
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadPhotosForDate(datePickerElement.value);
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadPhotosForDate(datePickerElement.value);
    });

    loadPhotosForDate(datePickerElement.value); // Initialement charger les photos pour la date actuelle
});

async function loadPhotosForDate(date) {
    document.getElementById('loading').style.display = 'flex'; // Afficher le loader
    const sectionId = '{{ Auth::user()->worker->currentSection->section->id }}';
    try {
        const childrenResponse = await fetch(`/api/children/section/${sectionId}/date/${date}`);
        const childrenData = await childrenResponse.json();

        const photoResponse = await fetch(`/api/photos/section/${sectionId}/date/${date}`);
        const photos = await photoResponse.json();
        displayChildrenWithPhotos(childrenData, photos);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader
    }
}

function displayChildrenWithPhotos(children, photos) {
    const container = document.getElementById('photo-container');
    container.innerHTML = '';
    children.forEach(child => {
        if (!child) return;
        const childPhotos = photos.filter(photo => photo.child_id === child.id);
        let photoHtml = childPhotos.map(photo => {
            // Utilisation de asset pour générer l'URL de l'image
            return `
                <div class="photo-entry" onclick="openPhotoModal(${child.id})">
                    <strong>${photo.description}</strong>
                    <img src="/storage/${photo.path}" alt="Child Photo" style="width: 100px; height: auto;">
                    <p>${new Date(photo.taken_at).toLocaleTimeString()}</p>
                </div>
            `;
        }).join('');

        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${child.firstname} ${child.lastname}</h3>
                        <div class="photo-details">${photoHtml}</div>
                        <button class="btn btn-primary" onclick="openPhotoModal(${child.id})">Ajouter une photo</button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });
}


function openPhotoModal(childId, description='', path='') {
    // Initialiser le formulaire dans le modal ici
    document.getElementById('childIdInput').value = childId;
    $('#photoModal').modal('show');
    console.log('Opening photo modal for child:', childId);
    
}


function previewImage() {
    var preview = document.getElementById('image_preview');
    var file = document.getElementById('photo_path').files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
        preview.style.display = 'block'; // Afficher l'image si un fichier est chargé
    }

    if (file) {
        reader.readAsDataURL(file); // Lire le fichier sélectionné et déclencher l'event onloadend
    } else {
        preview.src = "";
        preview.style.display = 'none'; // Cacher l'image si aucun fichier n'est sélectionné
    }
}

async function submitPhotoForm() {
    const form = document.getElementById('photoForm');
    const formData = new FormData(form);

     // Obtenir la date et l'heure, remplacer 'T' par un espace
     let takenAt = document.getElementById('taken_at').value.replace('T', ' ') + ':00';
    formData.set('taken_at', takenAt);  // Mettre à jour le formData avec la nouvelle chaîne de date

    formData.forEach((value, key) => {
        console.log(key, value);
    });

    try {
        const response = await fetch('/api/photos', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // 'Content-Type': 'application/json' <-- Cette ligne doit être retirée pour les FormData
            },

            body: formData
        });

        console.log('Body:', formData);

        if (response.ok) {
            const result = await response.json();  // Assurez-vous que le serveur renvoie du JSON
            console.log('Photo saved successfully:', result);
            $('#photoModal').modal('hide');
            loadPhotosForDate(document.getElementById('date-picker').value);
            alert('Photo enregistrée avec succès!');
        } else {
            const errorText = await response.text();  // Pour le débogage, obtenir la réponse du serveur même en cas d'erreur
            console.error('Error saving photo:', errorText);
            alert('Erreur lors de l\'enregistrement de la photo');
        }
    } catch (error) {
        console.error('Error saving photo:', error);
        alert('Erreur lors de l\'enregistrement de la photo');
    }
}

</script>
@endpush
