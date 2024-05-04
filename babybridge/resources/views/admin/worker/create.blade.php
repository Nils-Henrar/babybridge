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
            <div class="form-group
            @error('firstname')
            has-error
            @enderror">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname') }}">
                @error('firstname')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('lastname')
            has-error
            @enderror">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname') }}">
                @error('lastname')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('email')
            has-error
            @enderror">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                @error('email')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- langue -->
            <div class="form-group
            @error('language')
            has-error
            @enderror">
                <label for="language">Langue</label>
                <select class="form-control" id="language" name="language">
                    <option value="">-- Choisir une langue --</option>
                    <option value="fr" @if(old('language')=='fr' ) selected @endif>Français</option>
                    <option value="en" @if(old('language')=='en' ) selected @endif>Anglais</option>
                </select>
                @error('language')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('phone')
            has-error
            @enderror">
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Téléphone" value="{{ old('phone') }}">
                @error('phone')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('address')
            has-error
            @enderror">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Adresse" value="{{ old('address') }}">
                @error('address')
                <span class="help-block
                @error('address')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('postal_code')
            has-error
            @enderror">
                <label for="postal_code">Code postal</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ old('postal_code') }}">
                @error('postal_code')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('city')
            has-error
            @enderror">
                <label for="city">Ville</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="{{ old('city') }}">
                @error('city')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('section_id')
            has-error
            @enderror">
                <label for="section_id">Section</label>
                <select class="form-control" id="section_id" name="section_id">
                    <option value="">-- Choisir une section --</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" @if(old('section_id')==$section->id) selected @endif>{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('section_id')
                <span class="help-block">{{ $message }}</span>
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