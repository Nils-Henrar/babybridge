@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- formulaire de modification du profil d'un travailleur -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Modifier le profil du travailleur</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.worker.update', $worker->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group
            @error('firstname')
            has-error
            @enderror">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname', $worker->user->firstname) }}">
                @error('firstname')
                <span class="help-block
                @error('firstname')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('lastname')
            has-error
            @enderror">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname', $worker->user->lastname) }}">
                @error('lastname')
                <span class="help-block
                @error('lastname')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('email')
            has-error
            @enderror">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', $worker->user->email) }}">
                @error('email')
                <span class="help-block
                @error('email')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('phone')
            has-error
            @enderror">
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Téléphone" value="{{ old('phone', $worker->user->phone) }}">
                @error('phone')
                <span class="help-block
                @error('phone')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('address')
            has-error
            @enderror">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Adresse" value="{{ old('address', $worker->user->address) }}">
                @error('address')
                <span class="help-block
                @error('address')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('postal_code')
            has-error
            @enderror">
                <label for="postal_code">Code postal</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ old('postal_code', $worker->user->postal_code) }}">
                @error('postal_code')
                <span class="help-block
                @error('postal_code')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('city')
            has-error
            @enderror">
                <label for="city">Ville</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="{{ old('city', $worker->user->city) }}">
                @error('city')
                <span class="help-block
                @error('city')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('section_id')
            has-error
            @enderror">
                <label for="section_id">Section</label>
                <select class="form-control" id="section_id" name="section_id">
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" @if($worker->currentSections()->first()->section->id == $section->id) selected @endif>{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('section_id')
                <span class="help-block
                @error('section_id')
                has-error
                @enderror">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Modifier</button> <a href="{{ route('admin.worker.show', $worker->id) }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>

</div>
<!-- /.card -->

<!-- affichage des erreurs de validation -->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

@endsection