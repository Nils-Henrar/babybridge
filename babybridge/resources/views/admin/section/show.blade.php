@extends ('layouts.app')

@section('subtitle', 'Sections')

//titre nom de la section
@section('content_header_title', 'Section')


//sous titre nom de la section
@section('content_header_subtitle', $section->name)

@section('content_body')


<!-- tableau contenanant le nom et le prenom des puéricultrices/teurs -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title ">Puéricultrices de la section</h3>
        <div class="card-tools">
            <a href="{{ route('admin.worker.create') }}" class="btn btn-primary">Ajouter une puéricultrice</a>
        </div>
    </div>
    <!-- /.card-header -->

    <!-- message de succès après la création d'un utilisateur -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($section->currentWorkers() as $worker)
                <tr>
                    <td>{{ $worker->id }}</td>
                    <td>{{$worker->user->lastname}}</td>
                    <td>{{$worker->user->firstname}}</td>
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

<!-- tableau contenanant les enfants de la section -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Enfants de la section</h3>
        <div class="card-tools">
            <a href="{{ route('admin.child.create') }}" class="btn btn-primary">Ajouter un enfant</a>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach($section->currentChildren() as $child)
                <tr>
                    <td>{{ $child->id }}</td>
                    <td>{{$child->child->lastname}}</td>
                    <td>{{$child->child->firstname}}</td>
                    <td>
                        <a href="{{ route('admin.child.show', $child->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('admin.child.edit', $child->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('admin.child.destroy', $child->id) }}" method="POST" style="display:inline;">
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
<!-- retour -->
<a href="{{ route('admin.section.index') }}" class="btn btn-secondary">Retour</a>
<!-- /.card -->

@endsection