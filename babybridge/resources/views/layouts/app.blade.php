@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
{{ config('adminlte.title') }}
@hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
@hasSection('content_header_title')
<h1 class="text-muted">
    @yield('content_header_title')
    
    @hasSection('content_header_subtitle')
    <small class="text-dark">
        <i class="fas fa-xs fa-angle-right text-muted"></i>
        @yield('content_header_subtitle')
    </small>
    @endif
    @hasSection('extra-css')
    @yield('extra-css')
    @endif
</h1>
@endif
@stop

{{-- Add a common navbar layout --}}

@section('content_top_nav_right')
<!-- athu user name -->
<li class="nav-item d-none d-sm-inline-block">
    <span href="#" class="nav-link">{{ Auth::user()->login }}</span>
</li>
@endsection

{{-- Rename section content to content_body --}}

@section('content')
@yield('content_body')
@stack('scripts')
@stop

{{-- Create a common footer --}}

@section('footer')
<div class="float-right">
    Version: {{ config('app.version', '1.0.0') }}
</div>

<strong>
    <a href="{{ config('app.company_url', '#') }}">
        {{ config('app.company_name', 'My company') }}
    </a>
</strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<!-- Dans <head> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link href="https://cdn.jsdelivr.net/gh/eliyantosarage/font-awesome-pro@main/fontawesome-pro-6.5.2-web/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@endpush


{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    /*
.card-header {
border-bottom: none;
}
.card-title {
font-weight: 600;
}
*/
</style>
@endpush