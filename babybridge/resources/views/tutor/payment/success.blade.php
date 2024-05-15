@extends('layouts.app')

@section('title', 'Paiement Réussi')

@section('content')
<div class="container">
    <div class="alert alert-success">
        <h1>Paiement Réussi !</h1>
        <p>Merci d'avoir payé pour l'événement.</p>
        <p>Montant payé : {{ $payment->amount }} {{ $payment->currency }}</p>
        <p><a href="{{ route('home') }}" class="btn btn-primary">Retour à l'accueil</a></p>
    </div>
</div>
@endsection