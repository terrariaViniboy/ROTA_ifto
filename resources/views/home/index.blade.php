@extends('layouts.app')

@section('title', 'Eventos ativos — Rota IFTO')

@section('content')
    <section class="page-head">
        <div>
            <p class="card-kicker">Guia do participante</p>
            <h1 class="page-title">Eventos ativos</h1>
            <p class="muted">
                Escolha um evento para ver hospedagem, alimentação, transporte e pontos de emergência da cidade.
            </p>
        </div>
    </section>

    @if ($events->isEmpty())
        <x-empty-state message="Nenhum evento ativo agora. Quando houver um, ele aparece aqui." />
    @else
        <ul class="card-list">
            @foreach ($events as $event)
                <li class="card card--evento">
                    <h2 class="card-title">
                        <a href="{{ route('events.guide', $event) }}">{{ $event->name }}</a>
                    </h2>
                    <p class="muted">{{ $event->city }}</p>
                    <p class="muted">
                        {{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}
                    </p>
                    <p class="muted">{{ $event->location_address }}</p>
                    <div class="card-actions">
                        <a href="{{ route('events.guide', $event) }}" class="button">Abrir guia</a>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
