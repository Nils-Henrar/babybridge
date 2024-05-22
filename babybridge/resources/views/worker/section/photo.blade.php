@extends('layouts.app')

@section('subtitle', 'Photos des Enfants')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Photos')

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
    .photo-details {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }
    .photo-entry {
        position: relative;
        min-width: 150px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 10px;
        margin-right: 10px;
        text-align: center;
    }
    .photo-entry img {
        width: 100px;
        height: auto;
    }
    .photo-entry .delete-icon {
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
        font-size: 2rem;
        color: #176FA1;
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
    const token = `{{ session('authToken') }}`; // Récupérer le token stocké dans la session
    if (token) {
        sessionStorage.setItem('authToken', token);
        console.log('Token stored in session storage');
    }else{
        console.log('No token found');
    }
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
            const takenAt = new Date(photo.taken_at).toLocaleTimeString();
            return `
            <div class="photo-entry">
                <i class="delete-icon fas fa-times-circle" onclick="deletePhoto(${photo.id})"></i>
                <img src="/storage/${photo.path}" alt="Child Photo" class="large-icon">
                <div class="photo-description">${photo.description}</div>
                <div class="photo-time">${takenAt}</div>
                <button class="btn btn-info btn-sm mt-2" onclick="openPhotoModal(${child.id}, ${photo.id})">Modifier</button>
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
                    <div class="photo-details">${photoHtml}</div>
                    <button class="btn btn-primary" onclick="openPhotoModal(${child.id})"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });
}

async function submitPhotoForm() {
    const form = document.getElementById('photoForm');
    const formData = new FormData(form);
    
    const takenAtTime = document.getElementById('taken_at').value;
    const takenAtDate = document.getElementById('date-picker').value;

    const takenAt = `${takenAtDate} ${takenAtTime}:00`;

    formData.set('taken_at', takenAt);

    const photoId = document.getElementById('photoId').value;
    const token = sessionStorage.getItem('authToken'); // Récupérer le token stocké dans la session

    const url = photoId ? `/api/photos/${photoId}` : '/api/photos';
    const method = photoId ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': `Bearer ${token}` // Ajouter le token dans les headers
            },
            body: formData
        });

        console.log('Response:', response);

        formData.forEach((value, key) => console.log(key, value));
        
        if (response.ok) {
            const result = await response.json();
            console.log('Photo saved successfully:', result);
            $('#photoModal').modal('hide');
            loadPhotosForDate(document.getElementById('date-picker').value);
            alert('Photo enregistrée avec succès!');
        } else {
            const errorText = await response.text();
            console.error('Error saving photo:', errorText);
            alert('Erreur lors de l\'enregistrement de la photo');
        }
    } catch (error) {
        console.error('Error saving photo:', error);
        alert('Erreur lors de l\'enregistrement de la photo');
    }
}

function openPhotoModal(childId, photoId = null) {
    console.log('Opening photo modal for child:', childId);
    document.getElementById('childId').value = childId;
    document.getElementById('photoForm').reset();
    document.getElementById('image_preview').style.display = 'none';

    if (photoId) {
        fetch(`/api/photos/${photoId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('photoId').value = data.id;
                document.getElementById('photo_description').value = data.description;
                document.getElementById('taken_at').value = data.taken_at.substr(11, 5); // Extract only the time for the input
                document.getElementById('image_preview').src = `/storage/${data.path}`;
                document.getElementById('image_preview').style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading photo details:', error);
                alert('Failed to load photo details.');
            });
    } else {
        document.getElementById('photoId').value = '';
        document.getElementById('photo_description').value = '';
        document.getElementById('taken_at').value = '';
    }

    $('#photoModal').modal('show');
}

async function deletePhoto(photoId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette photo?')) return;

    const token = sessionStorage.getItem('authToken'); // Récupérer le token stocké dans la session

    try {
        const response = await fetch(`/api/photos/${photoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': `Bearer ${token}` // Ajouter le token dans les headers
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete photo');
        }

        const result = await response.json();
        console.log('Photo deleted successfully:', result);
        alert('Photo supprimée avec succès');
        loadPhotosForDate(document.getElementById('date-picker').value);
    } catch (error) {
        console.error('Error deleting photo:', error);
        alert('Erreur lors de la suppression de la photo');
    }
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

</script>
@endpush
