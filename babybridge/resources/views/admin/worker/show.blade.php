@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Puéricultrices/teurs')

@section('content_header_subtitle', $worker->user->fullname )

@section('content_body')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Profil du travailleur</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="{{ $worker->user->lastname }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Prénom</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Prénom" value="{{ $worker->user->firstname }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Email</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Email" value="{{ $worker->user->email }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Téléphone</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Téléphone" value="{{ $worker->user->phone }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Adresse</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Adresse" value="{{ $worker->user->address }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Code postal</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Code postal" value="{{ $worker->user->postal_code }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Ville</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Ville" value="{{ $worker->user->city }}" readonly>
        </div>
        <div class="form-group">
            <label for="name">Section</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Section" value="{{ $worker->currentSection->section->name }}" readonly>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('admin.worker.index') }}" class="btn btn-secondary mt-3">Retour à l'index</a>
    </div>
</div>

@endsection