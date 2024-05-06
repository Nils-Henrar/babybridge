@extends ('layouts.app')

@section('subtitle', 'Create Event')

@section('content_header_title', 'Events')

@section('content_header_subtitle', 'Create Event')

@section('content_body')


<!-- formulaire d'ajout d'un événement -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title ">Ajouter un événement</h3>
    </div>

    <!-- /.card-header -->
    <!-- form start -->
    <form method="POST" action="{{ route('admin.event.store') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Titre" value="{{ old('title') }}">
                @error('title')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="schedule">Date</label>
                <input type="date" class="form-control" id="schedule" name="schedule" placeholder="Date" value="{{ old('schedule') }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Description">{{ old('description') }}</textarea>
            </div>

            <!-- cocher les sections qui participent à l'événement avec un moyen de toutes les cocher/décocher -->
            <div class="form-group">
                <label>Sections</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="allSections" name="allSections" value="1">
                    <label class="form-check-label" for="allSections">Toutes les sections</label>

                    @foreach($sections as $section)
                    <div class="form-check ml-4">
                        <input class="form-check-input" type="checkbox" id="section{{ $section->id }}" name="sections[]" value="{{ $section->id }}">
                        <label class="form-check-label" for="section{{ $section->id }}">{{ $section->name }} ({{ $section->currentType->type->name ?? 'Aucun type' }})</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Ajouter</button> <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">Annuler</a>
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

@section('js')
<script>
    // cocher/décocher toutes les sections
    document.getElementById('allSections').addEventListener('change', function() {
        let sections = document.querySelectorAll('input[name="sections[]"]');
        sections.forEach(section => {
            section.checked = this.checked;
        });
    });
</script>
@endsection