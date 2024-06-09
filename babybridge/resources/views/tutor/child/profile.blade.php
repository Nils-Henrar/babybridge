@extends('layouts.app')

@section('subtitle', 'Profil de l\'Enfant')

@section('content_header_title', 'Profil de l\'Enfant')

@section('extra-css')
<style>
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
        <img src="{{ asset('storage/'.$child->photo_path) }}" class="img-fluid rounded-circle" alt="Photo de profil de {{ $child->firstname }}" style="width: 200px; height: 200px; object-fit: cover; float: right;">
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

            <!-- ajouter la photo du profile de l'enfant a droite de la box -->

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
                        <!-- taille identique pour toutes les images -->
                        <img src="{{ asset('storage/'.$photo->path) }}" class="img-fluid photo-thumbnail" data-photo="{{ asset('storage/'.$photo->path) }}" data-taken-at="{{ $photo->taken_at }}" alt="Photo" style="height: 200px; width: 100%; object-fit: cover;">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="CrossClose"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="photoModalImage" class="img-fluid" style="max-height: 80vh;">
                <p id="photoModalDate" class="mt-3"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModal">Fermer</button>
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
    });
</script>
@endpush
@endsection