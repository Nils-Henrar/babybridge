@extends ('layouts.app')

@section('subtitle', 'Création d\'un enfant')

@section('content_header_title', 'Création')

@section('content_header_subtitle', 'Enfant')

@section('content_body')

<!-- Formulaire de création d'un enfant -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Ajouter un enfant</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.child.store') }}">
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
            <!-- il faut ajouter un champ pour chaque tuteur de l'enfant afin de créer un nouveau utilisateur pour chaque tuteur -->
            <!-- il faut ajouter également un script javascript pour ajouter dynamiquement des champs pour les tuteurs en fonction de combien de tuteurs on veut ajouter -->

            <div id="tutor-template">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">Tuteur</h3>
                        <button type="button" class="btn btn-danger float-right remove-tutor" style="display: none;">Retirer</button>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tutor_lastname[]">Nom du Tuteur:</label>
                            <input type="text" class="form-control @error('tutor_lastname.*') is-invalid @enderror" name="tutor_lastname[]" placeholder="Nom du tuteur">
                            @error('tutor_lastname.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tutor_firstname[]">Prénom du Tuteur:</label>
                            <input type="text" class="form-control @error('tutor_firstname.*') is-invalid @enderror" name="tutor_firstname[]" placeholder="Prénom du tuteur">
                            @error('tutor_firstname.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tutor_email[]">Email du Tuteur:</label>
                            <input type="email" class="form-control @error('tutor_email.*') is-invalid @enderror" name="tutor_email[]" placeholder="Email du tuteur">
                            @error('tutor_email.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tutor_phone[]">Téléphone du Tuteur:</label>
                            <input type="text" class="form-control @error('tutor_phone.*') is-invalid @enderror" name="tutor_phone[]" placeholder="Téléphone du tuteur">
                            @error('tutor_phone.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- langue -->
                        <div class="form-group">
                            <label for="tutor_language[]">Langue</label>
                            <select class="form-control @error('tutor_language.*') is-invalid @enderror" name="tutor_language[]">
                                <option value="">-- Choisir une langue --</option>
                                <option value="fr">Français</option>
                                <option value="en">Anglais</option>
                            </select>
                            @error('tutor_language.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tutor_address[]">Adresse du Tuteur:</label>
                            <input type="text" class="form-control @error('tutor_address.*') is-invalid @enderror" name="tutor_address[]" placeholder="Adresse du tuteur">
                            @error('tutor_address.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tutor_postal_code[]">Code postal du Tuteur:</label>
                            <input type="text" class="form-control @error('tutor_postal_code.*') is-invalid @enderror" name="tutor_postal_code[]" placeholder="Code postal du tuteur">
                            @error('tutor_postal_code.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tutor_city[]">Ville du Tuteur:</label>
                            <input type="text" class="form-control @error('tutor_city.*') is-invalid @enderror" name="tutor_city[]" placeholder="Ville du tuteur">
                            @error('tutor_city.*')
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