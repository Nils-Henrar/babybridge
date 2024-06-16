@extends ('layouts.app')

@section('subtitle', 'Création d\'un enfant')

@section('content_header_title', 'Création')

@section('content_header_subtitle', 'Enfant')

@section('content_body')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Ajouter un enfant</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.child.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control @error('lastname') is-invalid @enderror" @if(old('name')) value="{{ old('lastname') }}" @endif id="lastname" name="lastname" placeholder="Nom" required>
                @error('lastname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control @error('firstname') is-invalid @enderror" @if(old('firstname')) value="{{ old('firstname') }}" @endif id="firstname" name="firstname" placeholder="Prénom" required>
                @error('firstname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="birthdate">Date de naissance</label>
                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" @if(old('birthdate')) value="{{ old('birthdate') }}" @endif id="birthdate" name="birthdate" placeholder="Date de naissance" required>
                @error('birthdate')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="photo">Photo de profil</label>
                <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" @if(old('photo')) value="{{ old('photo') }}" @endif>
                @error('photo')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="section">Section</label>
                <select class="form-control @error('section') is-invalid @enderror" id="section" name="section">
                    <option value="">-- Choisir une section --</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
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
                <textarea class="form-control @error('special_infos') is-invalid @enderror" id="special_infos" name="special_infos" placeholder="Informations particulières">@if(old('special_infos')){{ old('special_infos') }}@endif</textarea>
            </div>

            <div id="tutor-template">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tuteur</h3>
                        <button type="button" class="btn btn-danger float-right remove-tutor" style="display: none;">Retirer</button>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tutor_email[]">Email du Tuteur:</label>
                            <input type="email" class="form-control @error('tutor_email.*') is-invalid @enderror" name="tutor_email[]" placeholder="Email du tuteur" required>
                            @error('tutor_email.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div id="tutors-container"></div>

            <button type="button" id="add-tutor" class="btn btn-info">Ajouter un tuteur</button>
            <div class="card-footer">
                <a href="{{ route('admin.child.index') }}" class="btn btn-secondary mt-3">Annuler</a>
                <button type="submit" class="btn btn-primary mt-3">Créer</button>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var tutorCount = 0; // Initialise le compteur de tuteurs à 0
        $('#add-tutor').click(function() {
            tutorCount++; // Incrémente le compteur à chaque clic
            var newTutor = $('#tutor-template').clone().removeAttr('id').show(); 
            newTutor.find('.card-title').text('Tuteur ' + tutorCount);
            
            // Réinitialise la valeur de chaque champ
            newTutor.find('input, select').val('');

            // Ajoute un bouton pour retirer le tuteur
            newTutor.find('.remove-tutor').show();

            $('#tutors-container').append(newTutor);
        });

        // Écouteur d'événement pour retirer un tuteur
        $('#tutors-container').on('click', '.remove-tutor', function() {
            $(this).closest('.card').remove(); // Supprime seulement la carte du tuteur
            tutorCount--; // Décrémente le compteur de tuteurs

            // Si aucun tuteur n'est présent, cache le bouton "Retirer" du modèle de tuteur
            if (tutorCount === 0) {
                $('#tutor-template').find('.remove-tutor').hide();
            }
        });
    });
</script>

@endsection
