@extends('layouts.app')

@section('title', 'Eventos ativos — Rota IFTO')

@section('content')
    <section class="mb-8">
        <p class="text-sm font-medium uppercase tracking-wide text-emerald-900">Guia do participante</p>
        <h1 class="mt-2 text-3xl font-semibold">Eventos ativos</h1>
        <p class="mt-3 max-w-2xl text-stone-700">
            Escolha um evento para ver hospedagem, alimentação, transporte e pontos de emergência da cidade.
        </p>
    </section>

    @if ($events->isEmpty())
        <p class="rounded border border-dashed border-stone-300 bg-white px-4 py-8 text-center text-stone-600">
            Nenhum evento ativo no momento.
        </p>
    @else
        <ul class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($events as $event)
                <li class="rounded border border-stone-200 bg-white p-5">
                    <h2 class="text-xl font-semibold">
                        <a href="{{ route('events.guide', $event) }}" class="underline-offset-4 hover:underline">{{ $event->name }}</a>
                    </h2>
                    <p class="mt-2 text-sm text-stone-700">{{ $event->city }}</p>
                    <p class="mt-1 text-sm text-stone-700">
                        {{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}
                    </p>
                    <p class="mt-3 text-sm">{{ $event->location_address }}</p>
                    <a href="{{ route('events.guide', $event) }}" class="mt-4 inline-flex rounded bg-emerald-950 px-3 py-2 text-sm font-medium text-white">
                        Abrir guia
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
