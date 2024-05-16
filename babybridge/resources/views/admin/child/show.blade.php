@extends ('layouts.app')

@section('subtitle', 'Profil $child->fullname')

@section('content_header_title', 'Profil')

@section('content_header_subtitle', $child->fullname)

@section('content_body')

<!-- Affichage du profil de l'enfant -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Profil de l'enfant</h3>
    </div>
    <div class="card-body">

        <!-- Photo de profil-->
        <div class="text-center">
            <!-- aller chercher le full name slug -->
            <img src="{{ asset('storage/'.$child->photo_path)}}"class="img-fluid" style="height: 200px; width: 200px; object-fit: cover; border-radius: 50%;" alt="Photo de profil">
        </div>


        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="{{ $child->lastname }}" readonly>
        </div>
        <div class="form-group">
            <label for="firstname">Prénom</label>
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" value="{{ $child->firstname }}" readonly>
        </div>
        <div class="form-group">
            <label for="birthdate">Date de naissance</label>
            <input type="text" class="form-control" id="birthdate" name="birthdate" placeholder="Date de naissance" value="{{ $child->birthdate }} ({{ $child->age }} ans)" readonly>
        </div>
        <div class="form-group">
            <label for="section">Section</label>
            <input type="text" class="form-control" id="section" name="section" placeholder="Section" value="{{ $child->currentSection->section->name }}" readonly>
        </div>

        <!-- dossier médical de l'enfant -->
        <div class="form-group">
            <label for="medical_record">Informations particulières</label>
            <textarea class="form-control" id="medical_record" name="medical_record" placeholder="Dossier médical" readonly>{{ $child->special_infos}}</textarea>
        </div>

        <!-- affichage des tuteurs de l'enfant -->
        <!-- ajouter une unité à chaque label pour avoir tu1, tu2, tu3, etc. -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tuteurs</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($child->childTutors as $childTutor)
                <tr>
                    <td>{{ $childTutor->user->fullname }}</td>
                    <td>{{ $childTutor->user->email }}</td>
                    <td>{{ $childTutor->user->phone }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- ajouter un tuteur Todo -->



    </div>

    <div class="card-footer">
        <a href="{{ route('admin.child.index') }}" class="btn btn-primary">Retour à la liste d'enfants</a>
    </div>

</div>

@endsection