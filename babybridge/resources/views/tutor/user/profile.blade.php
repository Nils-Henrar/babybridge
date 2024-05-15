@extends('layouts.app')

@section('subtitle', 'Profil du Tuteur')

@section('content_header_title', 'Profil du Tuteur')

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
            <p><strong>Adresse :</strong> {{ $user->address }}</p>
            <p><strong>Code postal :</strong> {{ $user->zip }}</p>
            <p><strong>Ville :</strong> {{ $user->city }}</p>
            <p><strong>Téléphone :</strong> {{ $user->phone }}</p>
        </div>
    </div>

    <h4>Enfant{{ $user->children->count() > 1 ? 's' : '' }}</h4>
    @foreach ($user->children as $child)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $child->fullname }}</strong> ({{ $child->age }})
            </div>
            <div class="card-body">
                <p><strong>Date de naissance :</strong> {{ $child->birthdateForm }}</p>
                <p><strong>Informations spéciales :</strong> {{ $child->special_infos ?? 'N/A' }}</p>
                <a href="{{ route('tutor.child.profile', $child->id) }}" class="btn btn-info">Voir le profil</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
