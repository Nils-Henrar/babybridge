@extends('layouts.app')

@section('title', 'Paiement Annulé')

@section('content')
<div class="container">
    <div class="alert alert-danger">
        <h1>Paiement Annulé</h1>
        <p>Votre paiement a été annulé. Vous pouvez réessayer de payer l'événement.</p>
        <p><a href="{{ route('home') }}" class="btn btn-primary">Retour à l'accueil</a></p>
    </div>
</div>
@endsection