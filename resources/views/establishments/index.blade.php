@extends('layouts.app')

@section('title', 'Estabelecimentos — Rota IFTO')

@section('content')
    <div class="page-head">
        <h1 class="page-title">Estabelecimentos</h1>
        @if ($isAdmin)
            <a href="{{ route('establishments.create') }}" class="button">Novo estabelecimento</a>
        @endif
    </div>

    <div class="search">
        <label class="visually-hidden" for="busca-estabelecimentos">Buscar estabelecimento</label>
        <input id="busca-estabelecimentos" type="search" placeholder="Buscar: pizza, farmácia, táxi…" autocomplete="off">
    </div>

    @if ($establishments->isEmpty())
        <x-empty-state message="Ainda não há estabelecimentos cadastrados." />
    @else
        @php
            $grouped = $establishments->groupBy('category');
            $opened = false;
        @endphp

        @foreach (\App\Models\Establishment::categoryOptions() as $value => $label)
            @php
                $items = $grouped->get($value, collect());
                $isOpen = ! $opened && $items->isNotEmpty();
                if ($isOpen) {
                    $opened = true;
                }
            @endphp
            @if ($items->isNotEmpty())
                <details class="accordion" @if ($isOpen) open @endif>
                    <summary>
                        <span class="accordion-title">{{ $label }}</span>
                        <span class="accordion-count">({{ $items->count() }})</span>
                    </summary>
                    <div class="accordion-panel">
                        @foreach ($items as $establishment)
                            <article class="card card--{{ $establishment->category }}">
                                @if ($establishment->subcategory)
                                    <p class="card-kicker">{{ $establishment->subcategory }}</p>
                                @endif
                                <h2 class="card-title">
                                    <a href="{{ route('establishments.show', $establishment) }}">{{ $establishment->name }}</a>
                                </h2>
                                <p class="muted">{{ $establishment->event->name }}</p>
                                <p class="muted">{{ $establishment->address }}</p>
                                @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
                                    <p class="badge">{{ $establishment->group_capacity }} vagas</p>
                                @endif
                                @unless ($establishment->isMobilityApp())
                                    <div class="map-embed map-embed--card">
                                        <iframe
                                            src="{{ $establishment->googleMapsEmbedUrl() }}"
                                            title="Mapa de {{ $establishment->name }}"
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                        ></iframe>
                                    </div>
                                @endunless
                                <div class="card-actions">
                                    @if ($establishment->whatsappUrl())
                                        <a href="{{ $establishment->whatsappUrl() }}" class="button" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
                                    @endif
                                    @if ($establishment->isMobilityApp())
                                        @if ($establishment->android_app_url)
                                            <a href="{{ $establishment->android_app_url }}" class="button" target="_blank" rel="noopener noreferrer">Google Play</a>
                                        @endif
                                        @if ($establishment->ios_app_url)
                                            <a href="{{ $establishment->ios_app_url }}" class="button button--outline" target="_blank" rel="noopener noreferrer">App Store</a>
                                        @endif
                                    @else
                                        <a href="{{ $establishment->googleMapsUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
                                    @endif
                                    @if ($establishment->instagramUrl())
                                        <a href="{{ $establishment->instagramUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Instagram</a>
                                    @endif
                                    @if ($isAdmin)
                                        <a href="{{ route('establishments.edit', $establishment) }}" class="button button--outline">Editar</a>
                                        <form method="POST" action="{{ route('establishments.destroy', $establishment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button button--danger">Excluir</button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </details>
            @endif
        @endforeach
    @endif
@endsection
