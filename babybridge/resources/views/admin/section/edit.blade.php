@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- formulaire de modification d'une section -->

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Modifier une section</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.section.update', $section->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nom de la section</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nom de la section" value="{{ old('name', $section->name) }}">
                @error('name')
                <span class="help-block">{{ $message }}</span>
                @enderror

                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" @if($section->currentType->type->id == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('type')
                    <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Modifier</button> <a href="{{ route('admin.section.index') }}" class="btn btn-secondary">Annuler</a>
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