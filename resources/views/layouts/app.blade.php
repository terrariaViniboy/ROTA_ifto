<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Rota IFTO')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="page">
        <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>

        <header class="site-header">
            <div class="site-header-shapes" aria-hidden="true"></div>
            <div class="site-header-inner">
                <a class="brand" href="{{ route('home') }}">
                    <img class="brand-logo" src="{{ asset('images/logo-rota-ifto.jpg') }}" alt="" width="52" height="52">
                    <span class="brand-name">Rota IFTO</span>
                </a>
                <nav class="site-nav" aria-label="Seções">
                    <a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Início</a>
                    <a href="{{ route('events.index') }}" @if (request()->routeIs('events.*')) aria-current="page" @endif>Eventos</a>
                    <a href="{{ route('establishments.index') }}" @if (request()->routeIs('establishments.*')) aria-current="page" @endif>Estabelecimentos</a>
                </nav>
            </div>
            <div class="site-header-accent" aria-hidden="true"></div>
        </header>

        <main class="site-main" id="conteudo">
            @if (session('status'))
                <p class="notice" role="status">
                    {{ session('status') }}
                </p>
            @endif

            @yield('content')
        </main>

        <footer class="site-footer">
            <p>Rota IFTO · Guia do participante</p>
        </footer>
    </body>
</html>
