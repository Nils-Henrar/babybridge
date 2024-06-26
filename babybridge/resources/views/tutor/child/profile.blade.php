@extends('layouts.app')

@section('subtitle', 'Profil de l\'Enfant')

@section('content_header_title', 'Profil de l\'Enfant')

@section('extra-css')
<style>
    .photo-container {
        position: relative;
        display: inline-block;
        text-align: center;
        float: right;
        background-color: #176FA1;
        border-radius: 50%;

    }

    .photo-container img {
        transition: opacity 0.3s;
    }

    .photo-container:hover img,
    .photo-thumbnail-container:hover img {
        opacity: 0.3; /* Diminue l'opacité pour un effet sombre */
    }

    .photo-container .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 16px;
        display: none;
        pointer-events: none;
    }

    .photo-container:hover .overlay-text {
        display: block;
    }

    .photo-thumbnail-container {
        position: relative;
        display: inline-block;
        text-align: center;
        background-color: #176FA1
    }

    .photo-thumbnail-container img {
        transition: opacity 0.3s;
    }


    .photo-thumbnail-container .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 16px;
        display: none;
        pointer-events: none;
    }

    .photo-thumbnail-container:hover .overlay-text {
        display: block;
    }

    .photo-thumbnail {
        cursor: pointer;
    }
</style>
@endsection

@section('content_body')
<div class="container">
    <h3>Profil de {{ $child->firstname }} {{ $child->lastname }}</h3>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Informations personnelles</strong>
        </div>
        <div class="card-body">
            <div class="photo-container" data-toggle="modal" data-target="#uploadPhotoModal" style="cursor: pointer;">
                <img src="{{ asset('storage/'.$child->photo_path) }}" class="img-fluid rounded-circle" alt="Photo de profil de {{ $child->firstname }}" style="width: 200px; height: 200px; object-fit: cover;">
                <div class="overlay-text">Modifier la photo</div>
            </div>
            <p><strong>Nom :</strong> {{ $child->firstname }} {{ $child->lastname }}</p>
            <p><strong>Date de naissance :</strong> {{ $child->birthdateForm }}</p>
            <p><strong>Genre :</strong> {{ $child->gender }}</p>
            <p><strong>Informations spéciales :</strong> {{ $child->special_infos ?? 'N/A' }}</p>
            <p><strong>Section :</strong> {{ $child->currentSection->section->name }}</p>
            <p><strong>Puéricultrice{{ $child->sectionWorkers->count() > 1 ? 's' : '' }} :</strong>
                @foreach ($child->sectionWorkers as $sectionWorker)
                    {{ $sectionWorker->worker->user->fullname }}
                    @if ($loop->iteration == $loop->count - 1)
                        et
                    @elseif (!$loop->last)
                        ,
                    @endif
                @endforeach
            </p>
        </div>
    </div>

    <!-- Modal for uploading photo -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPhotoModalLabel">Changer la photo de profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="uploadPhotoForm" method="POST" action="{{ route('tutor.child.update_photo', $child->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="photo">Sélectionner une photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <div class="form-group mt-3">
                            <img id="previewImage" class="img-fluid" style="display: none; width: 200px; height: 200px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Photos</strong>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($child->photos as $photo)
                    <div class="col-md-3 mb-3">
                        <div class="photo-thumbnail-container">
                            <img src="{{ asset('storage/'.$photo->path) }}" class="img-fluid photo-thumbnail" data-photo="{{ asset('storage/'.$photo->path) }}" data-taken-at="{{ $photo->taken_at }}" alt="Photo" style="height: 200px; width: 200%; object-fit: cover;">
                            <div class="overlay-text">Aperçu</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="photoModalImage" class="img-fluid" style="max-height: 80vh;">
                <p id="photoModalDate" class="mt-3"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="prevPhoto">Précédente</button>
                <button type="button" class="btn btn-primary" id="nextPhoto">Suivante</button>
                <a href="" id="downloadPhoto" class="btn btn-success" download>Télécharger</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentPhotoIndex = 0;
        const photos = Array.from(document.querySelectorAll('.photo-thumbnail'));
        const photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
        const photoModalImage = document.getElementById('photoModalImage');
        const photoModalDate = document.getElementById('photoModalDate');
        const downloadPhoto = document.getElementById('downloadPhoto');

        function updatePhotoModal(index) {
            const photo = photos[index];
            const photoSrc = photo.getAttribute('data-photo');
            const photoDate = photo.getAttribute('data-taken-at');

            photoModalImage.src = photoSrc;
            photoModalDate.textContent = `Date: ${photoDate}`;
            downloadPhoto.href = photoSrc;
        }

        photos.forEach((photo, index) => {
            photo.addEventListener('click', function () {
                currentPhotoIndex = index;
                updatePhotoModal(currentPhotoIndex);
                photoModal.show();
            });
        });

        document.getElementById('prevPhoto').addEventListener('click', function () {
            if (currentPhotoIndex > 0) {
                currentPhotoIndex--;
                updatePhotoModal(currentPhotoIndex);
            }
        });

        document.getElementById('nextPhoto').addEventListener('click', function () {
            if (currentPhotoIndex < photos.length - 1) {
                currentPhotoIndex++;
                updatePhotoModal(currentPhotoIndex);
            }
        });

        document.getElementById('closeModal').addEventListener('click', function () {
            photoModal.hide();
        });

        // Prévisualiser l'image sélectionnée
        document.getElementById('photo').addEventListener('change', function (event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function (e) {
                const previewImage = document.getElementById('previewImage');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            }

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        });
    });
</script>
@endpush
@endsection
