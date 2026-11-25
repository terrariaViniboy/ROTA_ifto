@extends('layouts.app')

@section('title', $establishment->name.' — Rota IFTO')

@section('content')
    <p>
        <a href="{{ route('events.guide', $establishment->event) }}" class="text-link">Voltar ao guia de {{ $establishment->event->name }}</a>
    </p>

    <article class="card card--{{ $establishment->category }} with-space">
        <p class="card-kicker">
            {{ $establishment->categoryLabel() }}@if ($establishment->subcategory) · {{ $establishment->subcategory }}@endif
        </p>
        <h1 class="page-title">{{ $establishment->name }}</h1>
        <p class="muted">Código {{ $establishment->code }} · {{ $establishment->statusLabel() }}</p>

        @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
            <p class="badge">{{ $establishment->group_capacity }} vagas</p>
        @endif

        <dl class="meta">
            <div>
                <dt>Endereço</dt>
                <dd>{{ $establishment->address }}</dd>
            </div>
            <div>
                <dt>Evento</dt>
                <dd>{{ $establishment->event->name }} — {{ $establishment->event->city }}</dd>
            </div>
            <div>
                <dt>Capacidade do grupo</dt>
                <dd>{{ $establishment->group_capacity ?? 'Não informada' }}</dd>
            </div>
            <div>
                <dt>Preço</dt>
                <dd>{{ $establishment->price_range ?: 'Não informado' }}</dd>
            </div>
            @if ($establishment->instagramUrl())
                <div>
                    <dt>Instagram</dt>
                    <dd>
                        <a href="{{ $establishment->instagramUrl() }}" class="text-link" target="_blank" rel="noopener noreferrer">
                            {{ $establishment->instagramHandle() ?? $establishment->instagramUrl() }}
                        </a>
                    </dd>
                </div>
            @endif
        </dl>

        @unless ($establishment->isMobilityApp())
            <div class="map-embed map-embed--detail">
                <iframe
                    src="{{ $establishment->googleMapsEmbedUrl() }}"
                    title="Mapa de {{ $establishment->name }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                ></iframe>
            </div>
        @endunless

        <div class="card-actions">
            @if ($establishment->whatsappUrl())
                <a href="{{ $establishment->whatsappUrl() }}" class="button" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
            @elseif (! $establishment->isMobilityApp())
                <p class="muted">WhatsApp não informado.</p>
            @endif
            @if ($establishment->isMobilityApp())
                @if ($establishment->android_app_url)
                    <a href="{{ $establishment->android_app_url }}" class="button" target="_blank" rel="noopener noreferrer">Baixar no Google Play</a>
                @endif
                @if ($establishment->ios_app_url)
                    <a href="{{ $establishment->ios_app_url }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Baixar na App Store</a>
                @endif
            @else
                <a href="{{ $establishment->googleMapsUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
            @endif
            @if ($establishment->instagramUrl())
                <a href="{{ $establishment->instagramUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Instagram</a>
            @endif
        </div>
    </article>

    @if ($isAdmin)
        <div class="card-actions">
            <a href="{{ route('establishments.edit', $establishment) }}" class="button button--outline">Editar</a>
            <form method="POST" action="{{ route('establishments.destroy', $establishment) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="button button--danger">Excluir</button>
            </form>
        </div>
    @endif
@endsection
