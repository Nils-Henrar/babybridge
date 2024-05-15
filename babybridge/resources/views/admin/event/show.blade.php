@extends ('layouts.app')

@section('subtitle', 'Event')

@section('content_header_title', 'Events')

@section('content_header_subtitle', 'Event')

@section('content_body')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Événement</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $event->id }}</td>
                    </tr>
                    <tr>
                        <th>Titre</th>
                        <td>{{ $event->title }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $event->schedule }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $event->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            @foreach ($event->sections as $section)
            @if($section->childSections->where('to', null)->isEmpty())
            @else
            <div class="col-md-6"> <!-- Chaque tableau prend la moitié de l'espace de la ligne sur les écrans moyens et grands -->
                <h5>Section: {{ $section->name }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($section->childSections->where('to', null) as $childSection)



                            <tr>
                                <td>{{ $childSection->child->id }}</td>
                                <td>{{ $childSection->child->lastname }}</td>
                                <td>{{ $childSection->child->firstname }}</td>
                                <td>{{ $childSection->child->payments->where('event_id', $event->id)->first()->status }}
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @endforeach
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">Retour</a>
    </div>
</div>

@endsection