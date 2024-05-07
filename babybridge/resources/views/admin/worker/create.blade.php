@extends ('layouts.app')

@section('subtitle', 'Add Worker' )

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')

<!-- formulaire d'ajout d'une puéricultrice/teur -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Ajouter une puéricultrice/teur</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.worker.store') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname') }}">
                @error('firstname')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname') }}">
                @error('lastname')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                @error('email')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- langue -->
            <div class="form-group">
                <label for="language">Langue</label>
                <select class="form-control @error('language') is-invalid @enderror" id="language" name="language">
                    <option value="">-- Choisir une langue --</option>
                    <option value="fr" @if(old('language')=='fr' ) selected @endif>Français</option>
                    <option value="en" @if(old('language')=='en' ) selected @endif>Anglais</option>
                </select>
                @error('language')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Téléphone" value="{{ old('phone') }}">
                @error('phone')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Adresse" value="{{ old('address') }}">
                @error('address')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="postal_code">Code postal</label>
                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ old('postal_code') }}">
                @error('postal_code')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="city">Ville</label>
                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="Ville" value="{{ old('city') }}">
                @error('city')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="section_id">Section</label>
                <select class="form-control @error('section_id') is-invalid @enderror" id="section_id" name="section_id">
                    <option value="">-- Choisir une section --</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" @if(old('section_id')==$section->id) selected @endif>{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('section_id')
                <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Ajouter</button> <a href="{{ route('admin.worker.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>

</div>

<!-- /.card -->

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