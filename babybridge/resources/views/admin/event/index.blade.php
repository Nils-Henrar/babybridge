@extends ('layouts.app')

@section('subtitle', 'Events')

@section('content_header_title', 'Events')

@section('content_header_subtitle', 'Events')

@section('content_body')

<!-- Affichage de la liste des événements -->

<div class="card">


    <div class=" card-header">
        <h3 class="card-title">Liste des événements</h3>

        <div class="card-tools">
            <a href="{{ route('admin.event.create') }}" class="btn btn-primary">Ajouter un événement</a>
        </div>

        <!-- message de succès après la création d'un événement -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

    </div>


    <!-- /.card-header -->
    <div class="card-body">
        <table id="events" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Évenement</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($events as $event)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->schedule }}</td>
                    <td>{{ $event->description }}</td>
                    <td>
                        <a href="{{ route('admin.event.show', $event->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('admin.event.edit', $event->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" style="display:inline;" class=" delete-form">
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