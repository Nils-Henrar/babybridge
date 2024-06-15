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
        <form method="POST" action="{{ route('admin.child.update', $child->id) }}" enctype="multipart/form-data">
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

            <!-- upload de photo de profil -->
            <div class="form-group">
                <label for="photo">Photo de profil</label>

                <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" value="{{ $child->photo_path }}">
                @if($child->photo_path)
                <img src="{{ asset('storage/'.$child->photo_path) }}" class="img-fluid" style="height: 100px; width: 100px; object-fit: cover; border-radius: 50%;" alt="Photo de profil">
                @endif
                @error('photo')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <div class="form-group">
                <label for="section">Section</label>
                <select class="form-control @error('section') is-invalid @enderror" id="section" name="section">
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" @if($child->currentSection->id == $section->id) selected @endif>{{ $section->name }}</option>
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
                <textarea class="form-control @error('special_infos') is-invalid @enderror" id="special_infos" name="special_infos" placeholder="Informations particulières">{{ $child->special_infos }}</textarea>
            </div>
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