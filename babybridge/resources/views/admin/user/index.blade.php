@extends ('layouts.app')

@section('subtitle', 'Utilisateurs')

@section('content_header_title', 'Menu')

@section('content_header_subtitle', 'Utilisateurs')

@section('content_body')


<!-- Affichage de la liste des utilisateurs -->

<div class="card">


    <div class=" card-header">
        <h3 class="card-title">Liste des utilisateurs</h3>

        <div class="card-tools">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Ajouter un utilisateur</a>
        </div>

        <!-- message de succès après la création d'un utilisateur -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

    </div>


    <!-- /.card-header -->
    <div class="card-body">
        <table id="users" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Login</th>
                    <th>Roles</th>
                    <th>Email</th>
                    <th>Langue</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->lastname }}</td>
                    <td>{{ $user->firstname }}</td>
                    <td>{{ $user->login }}</td>
                    <td>
                        @foreach ($user->roles as $role)
                        <span class="badge badge-primary">{{ $role->role }}</span>
                        @endforeach
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->langue }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->postal_code }}</td>
                    <td>{{ $user->city }}</td>
                    <td>
                        <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-primary">Voir</a>
                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning">Modifier</a>
                        <form method="POST" action="{{ route('admin.user.destroy', $user->id) }}" style="display:inline;">
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

<!-- pagination -->

<div class="d-flex justify-content-center">

    {{ $users->links('pagination::bootstrap-5') }}
</div>

@endsection