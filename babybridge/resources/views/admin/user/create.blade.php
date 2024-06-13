@extends ('layouts.app')

@section('subtitle', 'Create User')

@section('content_header_title', 'Utilisateurs')

@section('content_header_subtitle', 'Ajouter un utilisateur')

@section('content_body')



<!-- formulaire d'ajout d'un utilisateur -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ajouter un utilisateur</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.user.invite') }}">
        @csrf
        <div class="card-body">
            <div class="form-group
            @error('firstname')
            has-error
            @enderror">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname') }}">
                @error('firstname')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('lastname')
            has-error
            @enderror">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname') }}">
                @error('lastname')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('email')
            has-error
            @enderror">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                @error('email')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- langue -->
            <div class="form-group
            @error('language')
            has-error
            @enderror">
                <label for="language">Langue</label>
                <select class="form-control" id="language" name="language">
                    <option value="">-- Choisir une langue --</option>
                    <option value="fr" @if(old('language')=='fr' ) selected @endif>Français</option>
                    <option value="en" @if(old('language')=='en' ) selected @endif>Anglais</option>
                </select>
                @error('language')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('phone')
            has-error
            @enderror">
                <label for="phone">Téléphone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Téléphone" value="{{ old('phone') }}">
                @error('phone')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('address')
            has-error
            @enderror">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Adresse" value="{{ old('address') }}">
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
                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Code postal" value="{{ old('postal_code') }}">
                @error('postal_code')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
            @error('city')
            has-error
            @enderror">
                <label for="city">Ville</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="{{ old('city') }}">
                @error('city')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div>
            <!-- Select role -->
            <div class="form-group">
                <label for="roles">Roles</label>
                <div>
                    @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role{{ $role->id }}">
                            <label class="form-check-label" for="role{{ $role->id }}">
                                {{ $role->role }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Multi-select for children -->
            <div class="form-group" id="childrenSelect" style="display: none;">
                <label for="children">Selectionnez les enfants</label>  
                <select id="children" name="children[]" class="form-control" multiple>
                    @foreach($children as $child)
                        <option value="{{ $child->id }}">{{ $child->firstname }} {{ $child->lastname }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Select section for workers -->
            <div class="form-group" id="sectionSelect" style="display: none;">
                <label for="section">Select Section</label>
                <select id="section" name="section" class="form-control">
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
                <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Ajouter</button> <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Annuler</a>
            </div>

        </div>
    </form>

</div>
<!-- récupérer les messages d'erreurs -->
<!-- dump le selcted roles -->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- /.card -->

@section('js')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const childrenSelect = new Choices('#children', {
            removeItemButton: true,
            searchResultLimit: 10,
            renderChoiceLimit: -1,
        });

        toggleFields();

        document.querySelectorAll('input[name="roles[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                toggleFields();
            });
        });
    });

    function toggleFields() {
        const roles = Array.from(document.querySelectorAll('input[name="roles[]"]:checked')).map(input => input.value);
        const childrenSelect = document.getElementById('childrenSelect');
        const sectionSelect = document.getElementById('sectionSelect');

        const tutorRoleId = `{{ $roles->firstWhere('role', 'tutor')->id }}`;
        const workerRoleId = `{{ $roles->firstWhere('role', 'worker')->id }}`;

        if (roles.includes(tutorRoleId)) {
            childrenSelect.style.display = 'block';
        } else {
            childrenSelect.style.display = 'none';
        }

        if (roles.includes(workerRoleId)) {
            sectionSelect.style.display = 'block';
        } else {
            sectionSelect.style.display = 'none';
        }
    }
</script>
@endsection

@endsection

