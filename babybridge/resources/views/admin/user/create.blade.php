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
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" required>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
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

