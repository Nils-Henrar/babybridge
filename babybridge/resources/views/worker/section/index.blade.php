@extends ('layouts.app')

@section('subtitle', 'Child')

@section('content_header_title', 'Children')

@section('content_header_subtitle', 'Child')

@section('content_body')

<!--  faire des small box pour les enfants -->

<div class="row">
    @foreach ($children as $child)
    <div class="col-lg-12 col-6">
        <!-- small box -->
        <div class="small-box" style="background-color:skyblue;">
            <div class="inner">
                <h3>{{ $child->firstname }}</h3>

                <p>{{ $child->lastname }}</p>

            </div>

            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="" class="small-box-footer">Profil <i class="fas fa-arrow-circle-right"></i></a>

            <!-- //mettre un bouton tout à droite pour ajouter un enfant -->


        </div>
    </div>
    @endforeach
</div>

@endsection