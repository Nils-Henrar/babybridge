@extends('layouts.app')

@section('subtitle', 'Profil de l\'Enfant')

@section('content_header_title', 'Profil de l\'Enfant')

@section('extra-css')
<style>
    .photo-thumbnail {
        width: 200px;
        height: 200px;
        object-fit: cover;
    }
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
        </div>
    </div>
</div>
@endsection
