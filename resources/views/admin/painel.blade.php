@extends('layouts.app')

@section('title', 'Painel admin — Rota IFTO')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
    <header class="page-head">
        <div>
            <p class="card-kicker">Administração</p>
            <h1 class="page-title">Painel</h1>
            <p class="muted">Você está logado. O cadastro de eventos e estabelecimentos nesta área entra na próxima etapa.</p>
        </div>
        <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="button button--outline">Sair</button>
        </form>
    </header>

    <div class="notice with-space" role="status">
        Login ativo. A sessão expira após um tempo sem uso (veja SESSION_LIFETIME no .env).
    </div>
@endsection
