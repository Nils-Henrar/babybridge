@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- Tableau affichant la liste des enfants -->

<div class="card">

    <div class=" card-header">
        <h3 class="card-title">Liste des enfants</h3>

        <div class="card-tools">
            <a href="{{ route('admin.child.create') }}" class="btn btn-primary">Ajouter un enfant</a>
        </div>

        <!-- message de succès après la création d'un enfant -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

    </div>


    <!-- /.card-header -->
    <div class="card-body">
        <table id="children" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de naissance</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($children as $child)
                <tr>
                    <td>{{ $child->id }}</td>
                    <td>{{ $child->lastname }}</td>
                    <td>{{ $child->firstname }}</td>
                    <td>{{ $child->birthdate }}</td>
                    <td>
                        {{$child->currentSection->section->name}}
                    </td>
                    <td>
                        <a href="{{ route('admin.child.show', $child->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('admin.child.edit', $child->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('admin.child.destroy', $child->id) }}" method="POST" style="display:inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Stop the form from submitting immediately
                if (confirm('Êtes-vous sûr de vouloir supprimer cette section ? Cette action est irréversible.')) {
                    this.submit(); // Submit the form if the user confirmed
                }
            });
        });
    });
</script>
@endsection