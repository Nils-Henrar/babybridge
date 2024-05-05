@extends ('layouts.app')

@section('subtitle', 'Modification d\'un enfant')

@section('content_header_title', 'Modification')

@section('content_header_subtitle', 'Enfant')

@section('content_body')

<!-- Formulaire de modification d'un enfant -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Modifier les informations de l'enfant</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.child.update', $child->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control @error('lastname') is-invalid @enderror" value="{{ $child->lastname }}" id="lastname" name="lastname" placeholder="Nom" required>
                @error('lastname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control @error('firstname') is-invalid @enderror" value="{{ $child->firstname }}" id="firstname" name="firstname" placeholder="Prénom" required>
                @error('firstname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="birthdate">Date de naissance</label>
                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" value="{{ $child->birthdate }}" id="birthdate" name="birthdate" placeholder="Date de naissance" required>
                @error('birthdate')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="section">Section</label>
                <select class="form-control @error('section') is-invalid @enderror" id="section" name="section">
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" @if($child->currentSection()->id == $section->id) selected @endif>{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('section')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="special_infos">Informations particulières</label>
                <textarea class="form-control" id="special_infos" name="special_infos" placeholder="Informations particulières">{{ $child->special_infos }}</textarea>
            </div>
            <!-- Afficher les tuteurs existants -->
            @foreach($child->childTutors as $tutor)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-bold">Tuteur de {{$child->fullname}}</h3>
                </div>
                <div class="card-body">

                    <input type="hidden" name="tutor_id[]" value="{{ $tutor->user->id }}">
                    <div class="form-group">
                        <label for="tutor_lastname">Nom du Tuteur:</label>
                        <input type="text" class="form-control" value="{{ $tutor->user->lastname }}" name="tutor_lastname[]" placeholder="Nom du tuteur">
                    </div>
                    <div class="form-group">
                        <label for="tutor_firstname">Prénom du Tuteur:</label>
                        <input type="text" class="form-control" value="{{ $tutor->user->firstname }}" name="tutor_firstname[]" placeholder="Prénom du tuteur">
                    </div>
                    <div class="form-group">
                        <label for="tutor_email">Email du Tuteur:</label>
                        <input type="email" class="form-control" value="{{ $tutor->user->email }}" name="tutor_email[]" placeholder="Email du tuteur">
                    </div>

                    <div class="form-group">
                        <label for="tutor_phone">Téléphone du Tuteur:</label>
                        <input type="text" class="form-control" value="{{ $tutor->user->phone }}" name="tutor_phone[]" placeholder="Téléphone du tuteur">
                    </div>

                    <div class="form-group">
                        <label for="tutor_language">Langue du Tuteur:</label>
                        <select class="form-control" name="tutor_language[]">
                            <option value="fr" @if($tutor->user->langue == 'fr') selected @endif>Français</option>
                            <option value="en" @if($tutor->user->langue == 'en') selected @endif>Anglais</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tutor_address">Adresse du Tuteur:</label>
                        <input type="text" class="form-control" value="{{ $tutor->user->address }}" name="tutor_address[]" placeholder="Adresse du tuteur">
                    </div>

                    <div class="form-group">
                        <label for="tutor_postal_code">Code postal du Tuteur:</label>
                        <input type="text" class="form-control" value="{{ $tutor->user->postal_code }}" name="tutor_postal_code[]" placeholder="Code postal du tuteur">
                    </div>

                    <div class="form-group">
                        <label for="tutor_city">Ville du Tuteur:</label>
                        <input type="text" class="form-control" value="{{ $tutor->user->city }}" name="tutor_city[]" placeholder="Ville du tuteur">
                    </div>
                </div>
            </div>
            @endforeach

            <div class="card-footer">
                <a href="{{ route('admin.child.index') }}" class="btn btn-secondary mt-3">Annuler</a>
                <button type="submit" class="btn btn-primary mt-3">Modifier</button>
            </div>
        </form>
    </div>
</div>

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