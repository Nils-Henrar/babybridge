@extends('layouts.app')

@section('subtitle', 'Profil du Tuteur')

@section('content_header_title', 'Profil du Tuteur')

@section('content')
<div class="container">
    <h3>{{ $user->firstname }} {{ $user->lastname }}</h3>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <strong>Informations personnelles</strong>
            <a href="{{ route('tutor.profile.edit') }}" class="btn btn-info ml-auto">Modifier</a>
        </div>
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $user->fullname}}</p>
            <p><strong>Login :</strong> {{ $user->login }}</p>
            <p><strong>Email :</strong> {{ $user->email }}</p>
            <p><strong>Adresse :</strong> {{ $user->address }}</p>
            <p><strong>Code postal :</strong> {{ $user->postal_code }}</p>
            <p><strong>Ville :</strong> {{ $user->city }}</p>
            <!-- si user->langue est égal à fr alors francias  sinon si user->langue est égal à en alors anglais sinon N/A -->
            <p><strong>Langue :</strong> {{ $user->langue == 'fr' ? 'Français' : ($user->langue == 'en' ? 'Anglais' : 'N/A') }}</p>
            <p><strong>Téléphone :</strong> {{ $user->phone }}</p>
        </div>
    </div>

    <h4>Enfant{{ $user->children->count() > 1 ? 's' : '' }}</h4>
    @foreach ($user->children as $child)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $child->fullname }}</strong> ({{ $child->age }})
            </div>
            <div class="card-body d-flex justify-content-between">
                <div>
                    <p><strong>Date de naissance :</strong> {{ $child->birthdateForm }}</p>
                    <p><strong>Informations spéciales :</strong> {{ $child->special_infos ?? 'N/A' }}</p>
                    <a href="{{ route('tutor.child.profile', $child->id) }}" class="btn btn-info">Voir le profil</a>
                </div>
                <img src="{{ asset('storage/'.$child->photo_path) }}" class="img-fluid rounded-circle ml-auto" alt="Photo de profil de {{ $child->firstname }}" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
        </div>
    @endforeach
</div>

<!-- message de confirmation -->
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
 

@endsection
