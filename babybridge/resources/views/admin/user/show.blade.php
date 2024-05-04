@extends ('layouts.app')

@section('subtitle', 'Profil')

@section('content_header_title', 'utilisateur')

@section('content_header_subtitle', 'Profil')

@section('content_body')

<!-- Affichage du profil de l'utilisateur -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Profil de l'utilisateur</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="{{ $user->lastname }}" readonly>
        </div>
        <div class="form-group">
            <label for="firstname">Prénom</label>
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" value="{{ $user->firstname }}" readonly>
        </div>
        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Login" value="{{ $user->login }}" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}" readonly>
        </div>
        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Téléphone" value="{{ $user->phone }}" readonly>
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Adresse" value="{{ $user->address }}" readonly>
        </div>
        <div class="form-group">
            <label for="postal_code">Code postal</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ $user->postal_code }}" readonly>
        </div>
        <div class="form-group">
            <label for="city">Ville</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="{{ $user->city }}" readonly>
        </div>

        <div class="form-group">
            <label for="role">Rôles</label>
            <input type="text" class="form-control" id="role" name="role" placeholder="Rôles" value="{{ implode(', ', $user->roles->pluck('role')->toArray()) }}" readonly>
        </div>
    </div>

    @endsection