@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- formulaire d'ajout d'une section -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Ajouter une section</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.section.store') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nom de la section</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nom de la section" value="{{ old('name') }}">
                @error('name')
                <span class="alert alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('type')
                <span class="alert alert-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Ajouter</button> <a href="{{ route('admin.section.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>

</div>
<!-- /.card -->

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