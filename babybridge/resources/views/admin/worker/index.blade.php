@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- Tableau contenanant les travailleurs -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title
        @if (count($workers) == 0)
        text-center
        @endif">Liste des travailleurs</h3>

        <div class="card-tools">
            <a href="{{ route('admin.worker.create') }}" class="btn btn-primary">Ajouter un travailleur</a>
        </div>

    </div>
    <!-- /.card-header -->

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>


                @foreach($workers as $worker)
                <tr>
                    <td>{{ $worker->id }}</td>
                    <td>{{$worker->user->lastname}}</td>
                    <td>{{$worker->user->firstname}}</td>
                    @foreach($worker->currentSections() as $section)
                    <td>{{ $section->section->name }}</td>
                    @endforeach
                    <td>

                        <a href="{{ route('admin.worker.show', $worker->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('admin.worker.edit', $worker->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('admin.worker.destroy', $worker->id) }}" method="POST" style="display:inline;">
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