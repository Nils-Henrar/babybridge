@extends ('layouts.app')

@section('subtitle', 'Admin')

@section('content_header_title', 'Admin')

@section('content_header_subtitle', 'Admin')

@section('content_body')


<!-- 
//crud section
Route::get('/admin/sections', [SectionController::class, 'index'])->name('admin.sections.index');
Route::get('/admin/sections/{id}', [SectionController::class, 'show'])->name('admin.sections.show');
Route::get('/admin/sections/create', [SectionController::class, 'create'])->name('admin.sections.create');
Route::post('/admin/sections', [SectionController::class, 'store'])->name('admin.sections.store');
Route::get('/admin/sections/{id}/edit', [SectionController::class, 'edit'])->name('admin.sections.edit');
Route::put('/admin/sections/{id}', [SectionController::class, 'update'])->name('admin.sections.update');
Route::delete('/admin/sections/{id}', [SectionController::class, 'destroy'])->name('admin.sections.destroy'); -->


<!-- tableau contenanant les sections -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des sections</h3>

        <div class="card-tools">
            <a href="{{ route('admin.section.create') }}" class="btn btn-primary">Ajouter une section</a>

        </div>

    </div>
    <!-- /.card-header -->

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Enfants</th>
                    <th>Puéricultrices/teurs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                <tr>
                    <td>{{ $section->id }}</td>
                    <td>{{ $section->name}}</td>
                    <td>{{ $section->countChildren() }}</td>
                    <td>{{ $section->countWorkers() }}</td>
                    <td>
                        <a href="{{ route('admin.section.show', $section->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('admin.section.edit', $section->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('admin.section.destroy', $section->id) }}" method="POST" style="display:inline;" class="delete-form">
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

</div>


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



@endsection