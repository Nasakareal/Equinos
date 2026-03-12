@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="icon" href="{{ asset('Favicons.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@stop

@php
    $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $login_url = $login_url ? route($login_url) : '';
        $register_url = $register_url ? route($register_url) : '';
        $password_reset_url = $password_reset_url ? route($password_reset_url) : '';
    } else {
        $login_url = $login_url ? url($login_url) : '';
        $register_url = $register_url ? url($register_url) : '';
        $password_reset_url = $password_reset_url ? url($password_reset_url) : '';
    }
@endphp

@section('adminlte_css')
<style>
    :root{
        --sv-text:#2f3d2f;
        --sv-muted:#5e6655;
        --sv-muted-2:#7f8478;
        --sv-stroke:rgba(191,175,145,.32);
        --sv-card:rgba(252,248,239,.97);
        --sv-card-2:rgba(241,234,219,.94);
        --sv-shadow:0 24px 60px rgba(54,62,42,.16);
        --sv-accent:#8d6a50;
        --sv-accent-deep:#6f4e38;
        --sv-focus:rgba(111,144,105,.18);
    }

    body.login-page{
        font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif !important;
        color: var(--sv-text) !important;
        background:
            radial-gradient(900px 540px at 12% 10%, rgba(120,149,103,.16), transparent 62%),
            radial-gradient(760px 420px at 88% 18%, rgba(160,114,79,.16), transparent 58%),
            linear-gradient(180deg, #d6d2c4 0%, #cec8b7 100%) !important;
        min-height: 100vh;
    }

    .login-box{
        width: min(430px, calc(100% - 40px));
    }

    .login-box > center img{
        filter: drop-shadow(0 14px 20px rgba(79, 63, 40, .18));
        margin-bottom: 8px;
    }

    .login-box .card{
        background: linear-gradient(180deg, var(--sv-card), var(--sv-card-2)) !important;
        border: 1px solid rgba(191,175,145,.42) !important;
        border-radius: 26px !important;
        box-shadow: var(--sv-shadow) !important;
        overflow: hidden;
    }

    .login-box .card-body{
        background: transparent !important;
        padding: 22px 22px 18px !important;
    }

    .login-logo,
    .login-box-msg{
        color: var(--sv-text) !important;
    }

    .login-box .form-control{
        background: rgba(255,252,245,.92) !important;
        border: 1px solid rgba(191,175,145,.44) !important;
        color: var(--sv-text) !important;
        border-radius: 15px !important;
        height: 46px;
        padding-left: 14px;
        box-shadow: inset 0 1px 2px rgba(98,90,70,.05);
        transition: .18s ease;
    }

    .login-box .form-control::placeholder{
        color: var(--sv-muted-2) !important;
    }

    .login-box .form-control:focus{
        background: #fffdf8 !important;
        border-color: rgba(111,144,105,.55) !important;
        box-shadow: 0 0 0 .15rem var(--sv-focus) !important;
        color: var(--sv-text) !important;
    }

    .login-box .input-group-text{
        background: rgba(255,252,245,.92) !important;
        border: 1px solid rgba(191,175,145,.44) !important;
        border-left: none !important;
        color: var(--sv-muted) !important;
        border-radius: 0 15px 15px 0 !important;
        width: 46px;
        justify-content: center;
    }

    .login-box .input-group .form-control{
        border-right: none !important;
        border-radius: 15px 0 0 15px !important;
    }

    .icheck-primary label{
        color: var(--sv-muted) !important;
        font-weight: 700;
        font-size: 13px;
    }

    .icheck-primary input:first-child:checked + label::before{
        background-color: #6e8d62 !important;
        border-color: #56704e !important;
    }

    .login-box .btn-primary{
        border: 1px solid #6f4e38 !important;
        background: linear-gradient(135deg, #8d6a50, #6f4e38) !important;
        color: #fff7ef !important;
        border-radius: 15px !important;
        font-weight: 800 !important;
        height: 46px;
        box-shadow: 0 14px 28px rgba(90,81,60,.18);
        transition: .18s ease;
    }

    .login-box .btn-primary:hover{
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    .login-box a{
        color: #6f4e38 !important;
        font-weight: 700;
    }

    .login-box a:hover{
        color: #5c3f2d !important;
        text-decoration: none;
    }

    .invalid-feedback{
        color: #9d6d47 !important;
        font-weight: 700;
    }
</style>
@stop

@section('auth_body')
    <form action="{{ $login_url }}" method="POST">
        @csrf

        {{-- Email --}}
        <div class="input-group mb-3">
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="{{ __('adminlte::adminlte.email') }}"
                required
                autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="input-group mb-3">
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}"
                required>
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

        <div class="row" style="align-items:center;">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button
                    type="submit"
                    class="btn btn-primary btn-block">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
    </form>
@stop
