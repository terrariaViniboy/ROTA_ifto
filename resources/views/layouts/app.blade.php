<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Rota IFTO')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
        <header class="bg-emerald-950 text-white">
            <div class="mx-auto flex max-w-5xl flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('home') }}" class="text-lg font-semibold">Rota IFTO</a>
                <nav class="flex flex-wrap gap-4 text-sm">
                    <a href="{{ route('home') }}" class="underline-offset-4 hover:underline">Início</a>
                    <a href="{{ route('events.index') }}" class="underline-offset-4 hover:underline">Eventos</a>
                    <a href="{{ route('establishments.index') }}" class="underline-offset-4 hover:underline">Estabelecimentos</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 py-8">
            @if (session('status'))
                <p class="mb-6 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">
                    {{ session('status') }}
                </p>
            @endif

            @yield('content')
        </main>
    </body>
</html>
