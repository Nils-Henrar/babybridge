@extends ('layouts.app')

@section('subtitle', 'Update User')

@section('content_header_title', 'Utilisateurs')

@section('content_header_subtitle', 'Modifier un utilisateur')

@section('content_body')


<!-- formulaire de modification d'un utilisateur -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title
        @error('firstname')
        has-error
        @enderror">Modifier un utilisateur</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.user.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group
            @error('firstname')
            has-error
            @enderror">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname', $user->firstname) }}">
                @error('firstname')
                <span class="help-block
                @error('firstname')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('lastname')
            has-error
            @enderror">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname', $user->lastname) }}">
                @error('lastname')
                <span class="help-block
                @error('lastname')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('email')
            has-error
            @enderror">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                @error('email')
                <span class="help-block
                @error('email')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('language')
            has-error
            @enderror">
                <label for="language">Langue</label>
                <select class="form-control" id="language" name="language">
                    <option value="">-- Choisir une langue --</option>
                    <option value="fr" @if(old('language', $user->langue)=='fr' ) selected @endif>Français</option>
                    <option value="en" @if(old('language', $user->langue)=='en' ) selected @endif>Anglais</option>
                </select>
                @error('language')
                <span class="help-block
                @error('language')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('phone')
            has-error
            @enderror">
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Téléphone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                <span class="help-block
                @error('phone')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('address')
            has-error
            @enderror">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Adresse" value="{{ old('address', $user->address) }}">
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
                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ old('postal_code', $user->postal_code) }}">
                @error('postal_code')
                <span class="help-block
                @error('postal_code')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('city')
            has-error
            @enderror">
                <label for="city">Ville</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="{{ old('city', $user->city) }}">
                @error('city')
                <span class="help-block
                @error('city')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('roles')
            has-error
            @enderror">
                <label for="roles">Rôles</label>
                <!-- checkbox -->
                @foreach($roles as $role)
                <div class="form-check
                @error('roles')
                has-error
                @enderror">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" @if(in_array($role->id, $user->roles->pluck('id')->toArray())) checked @endif>
                    <label class="form-check
                    @error('roles')
                    has-error
                    @enderror" for="roles">{{ $role->role }}</label>
                </div>
                @endforeach
                @error('roles')
                <span class="help-block
                @error('roles')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Modifier</button> <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Annuler</a>
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