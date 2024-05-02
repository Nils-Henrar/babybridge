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
            <div class="form-group
            @error('name')
            has-error
            @enderror">
                <label for="name">Nom de la section</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom de la section" value="{{ old('name') }}">
                @error('name')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>

</div>
<!-- /.card -->

@endsection