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

    {{-- Other user information fields --}}

    <div class="input-group mb-3">
        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}" placeholder="{{ __('Prénom') }}">
        <div class="input-group-append">
            @error('firstname')
            <div class="input-group-text">
                <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" placeholder="{{ __('Nom') }}">
        <div class="input-group-append">
            @error('lastname')
            <div class="input-group-text">
                <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="{{ __('Adresse') }}">
        <div class="input-group-append">
            @error('address')
            <div class="input-group-text">
                <span class="fas fa-map-marker-alt {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}" placeholder="{{ __('Code postal') }}">
        <div class="input-group-append">
            @error('postal_code')
            <div class="input-group-text">
                <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="{{ __('Ville') }}">
        <div class="input-group-append">
            @error('city')
            <div class="input-group-text">
                <span class="fas fa-city {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="{{ __('Téléphone') }}">
        <div class="input-group-append">
            @error('phone')
            <div class="input-group-text">
                <span class="fas fa-phone {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>

    <div class="input-group mb-3">
        <select name="language" class="form-control @error('language') is-invalid @enderror">
            <option value="">{{ __('-- Choisir une langue --') }}</option>
            <option value="fr" @if(old('language') == 'fr') selected @endif>{{ __('Français') }}</option>
            <option value="en" @if(old('language') == 'en') selected @endif>{{ __('Anglais') }}</option>
        </select>
        <div class="input-group-append">
            @error('language')
            <div class="input-group-text">
                <span class="fas fa-language {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
            @enderror
        </div>
    </div>

    {{-- Register button --}}
    <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
        <span class="fas fa-user-plus"></span>
        {{ __('adminlte::adminlte.register') }}
    </button>

</form>

<!-- erreurs -->

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@stop





@section('auth_footer')
<p class="my-0">
    <a href="{{ $login_url }}">
        {{ __('adminlte::adminlte.i_already_have_a_membership') }}
    </a>
</p>
@stop
