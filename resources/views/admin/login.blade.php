@extends('layouts.app')

@section('title', 'Admin — Rota IFTO')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
    <div class="login-panel">
        <header class="login-panel-head">
            <h1 class="page-title">Entrar</h1>
        </header>

        <form method="post" action="{{ route('admin.login.submit') }}" class="card form-stack">
            @csrf

            @if ($errors->any())
                <p class="form-error" role="alert">{{ $errors->first() }}</p>
            @endif

            <div class="form-field">
                <label for="username">Usuário</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="form-field">
                <label for="password">Senha</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="button">Entrar</button>
        </form>
    </div>
@endsection
