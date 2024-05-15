@extends('layouts.app')

@section('subtitle', 'Profil de la Puéricultrice')

@section('content_header_title', 'Profil de la Puéricultrice')

@section('content')
<div class="container">
    <h3>Profil de {{ $user->firstname }} {{ $user->lastname }}</h3>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Informations personnelles</strong>
        </div>
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $user->fullname}}</p>
            <p><strong>Login :</strong> {{ $user->login }}</p>
            <p><strong>Email :</strong> {{ $user->email }}</p>
            <p><strong>Téléphone :</strong> {{ $user->phone }}</p>
            <p><strong>Adresse :</strong> {{ $user->address }}</p>
            <p><strong>Code postal :</strong> {{ $user->postal_code }}</p>
            <p><strong>Ville :</strong> {{ $user->city }}</p>
            <p><strong>Section :</strong> {{ $user->worker->currentSection->section->name }}</p>
        </div>
    </div>

    <!-- Ajoutez ici toute autre information pertinente concernant le worker -->
</div>
@endsection
