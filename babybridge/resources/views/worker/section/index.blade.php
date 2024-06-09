@extends ('layouts.app')

@section('subtitle', 'Child')

@section('content_header_title', 'Children')

@section('content_header_subtitle', 'Child')

@section('extra-css')
<style>
    .small-box {
        position: relative;
        background-color: #f0f0f0;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
    }

    .child-photo {
        margin-right: 15px;
    }

    .child-photo img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #176FA1;
    }

    .child-info {
        flex-grow: 1;
        color: #176FA1;
    }

    .child-info h3,
    .child-info p {
        margin: 0;
    }

    .ml-auto {
        margin-left: auto;
    }

    .small-box-footer {
        color: #176FA1;
        /* augmenter la taille */
        font-size: 1.2em;
    }
</style>
@endsection

@section('content_body')
<div class="container">
    <div class="row">
        @foreach ($children as $child)
        <div class="col-lg-12 col-6">
            <!-- small box -->
            <div class="small-box d-flex align-items-center">
                <div class="child-photo">
                    <img src="{{ asset('storage/'.$child->photo_path) }}" class="img-fluid rounded-circle" alt="Photo de profil de {{ $child->firstname }}">
                </div>
                <div class="child-info">
                    <h3>{{ $child->firstname }}</h3>
                    <p>{{ $child->lastname }}</p>
                </div>
                <div class="ml-auto">
                    <a href="{{ route('worker.child.profile', $child->id) }}" class="small-box-footer">Profil <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


