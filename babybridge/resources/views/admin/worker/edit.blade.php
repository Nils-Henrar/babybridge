@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- formulaire de modification du profil d'un travailleur -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Modifier le profil du travailleur</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.worker.update', $worker->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname', $worker->user->firstname) }}">
                @error('firstname')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname', $worker->user->lastname) }}">
                @error('lastname')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email', $worker->user->email) }}">
                @error('email')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="language">Langue</label>
                <select class="form-control" id="language" name="language">
                    <option value="">-- Choisir une langue --</option>
                    <option value="fr" @if(old('language', $worker->user->langue)=='fr' ) selected @endif>Français</option>
                    <option value="en" @if(old('language', $worker->user->langue)=='en' ) selected @endif>Anglais</option>
                </select>
                @error('language')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Téléphone" value="{{ old('phone', $worker->user->phone) }}">
                    @error('phone')
                    <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Adresse" value="{{ old('address', $worker->user->address) }}">
                    @error('address')
                    <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="postal_code">Code postal</label>
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ old('postal_code', $worker->user->postal_code) }}">
                    @error('postal_code')
                    <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="city">Ville</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="Ville" value="{{ old('city', $worker->user->city) }}">
                    @error('city')
                    <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="section_id">Section</label>
                    <select class="form-control @error('section_id') is-invalid @enderror" id="section_id" name="section_id">
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}" @if($worker->currentSection->section->id == $section->id) selected @endif>{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('section_id')
                    <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Modifier</button> <a href="{{ route('admin.worker.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
    </form>

</div>
<!-- /.card -->

<!-- affichage des erreurs de validation -->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection