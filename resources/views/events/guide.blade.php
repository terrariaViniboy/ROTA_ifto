@extends('layouts.app')

@section('title', $event->name.' — Guia')

@section('content')
    <p class="text-sm">
        <a href="{{ route('home') }}" class="text-emerald-900 underline-offset-4 hover:underline">Início</a>
    </p>

    <header class="mt-3 rounded border border-stone-200 bg-white p-5">
        <h1 class="text-3xl font-semibold">{{ $event->name }}</h1>
        <dl class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
            <div>
                <dt class="font-medium text-stone-500">Cidade</dt>
                <dd>{{ $event->city }}</dd>
            </div>
            <div>
                <dt class="font-medium text-stone-500">Período</dt>
                <dd>{{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="font-medium text-stone-500">Local</dt>
                <dd>{{ $event->location_address }}</dd>
            </div>
        </dl>
    </header>

    <nav class="mt-6 flex flex-wrap gap-2" aria-label="Filtrar por categoria">
        <a
            href="{{ route('events.guide', $event) }}"
            class="rounded-full border px-3 py-1.5 text-sm {{ $selectedCategory === null ? 'border-emerald-950 bg-emerald-950 text-white' : 'border-stone-300 bg-white text-stone-900' }}"
            @if ($selectedCategory === null) aria-current="true" @endif
        >Todos</a>
        @foreach (\App\Models\Establishment::categoryOptions() as $value => $label)
            @if ($value === \App\Models\Establishment::CATEGORY_EMERGENCIA)
                <a
                    href="{{ route('events.guide', ['event' => $event, 'category' => $value]) }}"
                    class="rounded-full bg-red-600 px-3 py-1.5 text-sm font-semibold text-white {{ $selectedCategory === $value ? 'ring-2 ring-red-300 ring-offset-2' : '' }}"
                    @if ($selectedCategory === $value) aria-current="true" @endif
                >{{ $label }}</a>
            @else
                <a
                    href="{{ route('events.guide', ['event' => $event, 'category' => $value]) }}"
                    class="rounded-full border px-3 py-1.5 text-sm {{ $selectedCategory === $value ? 'border-emerald-950 bg-emerald-950 text-white' : 'border-stone-300 bg-white text-stone-900' }}"
                    @if ($selectedCategory === $value) aria-current="true" @endif
                >{{ $label }}</a>
            @endif
        @endforeach
    </nav>

    @if ($establishments->isEmpty())
        <p class="mt-6 rounded border border-dashed border-stone-300 bg-white px-4 py-8 text-center text-stone-600">
            Nenhum estabelecimento nesta categoria.
        </p>
    @else
        <ul class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($establishments as $establishment)
                <li class="flex flex-col rounded border border-stone-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-emerald-900">{{ $establishment->categoryLabel() }}</p>
                            <h2 class="mt-1 text-xl font-semibold">
                                <a href="{{ route('establishments.show', $establishment) }}" class="underline-offset-4 hover:underline">{{ $establishment->name }}</a>
                            </h2>
                            <p class="mt-1 text-sm text-stone-700">{{ $establishment->address }}</p>
                        </div>
                        <p class="rounded-full bg-stone-100 px-3 py-1 text-sm">{{ $establishment->statusLabel() }}</p>
                    </div>

                    @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
                        <p class="mt-3">
                            <span class="inline-flex rounded-full bg-amber-400 px-2 py-1 text-xs font-semibold text-amber-950">{{ $establishment->group_capacity }} vagas</span>
                        </p>
                    @endif

                    <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                        @if ($establishment->whatsappUrl())
                            <a href="{{ $establishment->whatsappUrl() }}" class="inline-flex flex-1 justify-center rounded bg-emerald-800 px-3 py-2 text-center text-sm font-medium text-white" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
                        @endif
                        <a href="{{ $establishment->googleMapsUrl() }}" class="inline-flex flex-1 justify-center rounded border border-emerald-800 px-3 py-2 text-center text-sm font-medium text-emerald-950" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
