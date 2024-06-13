@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = route('register.complete') )

@section('auth_header', __('adminlte::adminlte.register_message'))

@section('auth_body')
<form action="{{ $register_url }}" method="post">
    @csrf

    {{-- Hidden fields for user information --}}
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="firstname" value="{{ $firstname }}">
    <input type="hidden" name="lastname" value="{{ $lastname }}">
    @if ($section_id)
        <input type="hidden" name="section_id" value="{{ $section_id }}">
    @endif

    @if ($child_ids)
        @foreach ($child_ids as $child_id)
            <input type="hidden" name="child_ids[]" value="{{ $child_id }}">
        @endforeach
    @endif

    {{-- Login field --}}
    <div class="input-group mb-3">
        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" placeholder="{{ __('Identifiant') }}" autofocus>

        <div class="input-group-append">
            @error('login')
            <div class="input-group-text">
                <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>

    </div>

    {{-- Password field --}}
    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('adminlte::adminlte.password') }}">

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
        </div>

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    {{-- Confirm password field --}}
    <div class="input-group mb-3">
        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('adminlte::adminlte.retype_password') }}">

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
        </div>

        @error('password_confirmation')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    {{-- Register button --}}
    <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
        <span class="fas fa-user-plus"></span>
        {{ __('adminlte::adminlte.register') }}
    </button>

</form>
@stop

@section('auth_footer')
<p class="my-0">
    <a href="{{ $login_url }}">
        {{ __('adminlte::adminlte.i_already_have_a_membership') }}
    </a>
</p>
@stop