@extends('layouts.app')

@section('title', $event->name.' — Guia')

@section('content')
    <p>
        <a href="{{ route('home') }}" class="text-link">Início</a>
    </p>

    <header class="card card--evento with-space">
        <h1 class="page-title">{{ $event->name }}</h1>
        <dl class="meta">
            <div>
                <dt>Cidade</dt>
                <dd>{{ $event->city }}</dd>
            </div>
            <div>
                <dt>Período</dt>
                <dd>{{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt>Local</dt>
                <dd>{{ $event->location_address }}</dd>
            </div>
        </dl>
    </header>

    <div class="search with-space">
        <label class="visually-hidden" for="busca-guia">Buscar no guia</label>
        <input id="busca-guia" type="search" placeholder="Buscar: pizza, farmácia, táxi…" autocomplete="off">
    </div>

    <nav class="pill-row" aria-label="Filtrar por categoria">
        <a
            href="{{ route('events.guide', $event) }}"
            class="pill"
            @if ($selectedCategory === null) aria-current="true" @endif
        >Todos</a>
        @foreach (\App\Models\Establishment::categoryOptions() as $value => $label)
            <a
                href="{{ route('events.guide', ['event' => $event, 'category' => $value]) }}"
                class="pill {{ $value === \App\Models\Establishment::CATEGORY_EMERGENCIA ? 'pill--alert' : '' }}"
                @if ($selectedCategory === $value) aria-current="true" @endif
            >{{ $label }}</a>
        @endforeach
    </nav>

    @if ($establishments->isEmpty())
        <div class="with-space">
            <x-empty-state message="Nenhum lugar nesta categoria ainda." />
        </div>
    @else
        <ul class="card-list with-space">
            @foreach ($establishments as $establishment)
                <li class="card card--{{ $establishment->category }}">
                    <div>
                        <p class="card-kicker">{{ $establishment->categoryLabel() }}</p>
                        <h2 class="card-title">
                            <a href="{{ route('establishments.show', $establishment) }}">{{ $establishment->name }}</a>
                        </h2>
                        <p class="muted">{{ $establishment->address }}</p>
                    </div>
                    <p class="badge">{{ $establishment->statusLabel() }}</p>

                    @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
                        <p class="badge">{{ $establishment->group_capacity }} vagas</p>
                    @endif

                    <div class="card-actions">
                        @if ($establishment->whatsappUrl())
                            <a href="{{ $establishment->whatsappUrl() }}" class="button" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
                        @endif
                        <a href="{{ $establishment->googleMapsUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
