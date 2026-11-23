@extends('layouts.app')

@section('title', $establishment->name.' — Rota IFTO')

@section('content')
    <p>
        <a href="{{ route('events.guide', $establishment->event) }}" class="text-link">Voltar ao guia de {{ $establishment->event->name }}</a>
    </p>

    <article class="card card--{{ $establishment->category }} with-space">
        <p class="card-kicker">{{ $establishment->categoryLabel() }}</p>
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
        </dl>

        <div class="card-actions">
            @if ($establishment->whatsappUrl())
                <a href="{{ $establishment->whatsappUrl() }}" class="button" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
            @else
                <p class="muted">WhatsApp não informado.</p>
            @endif
            <a href="{{ $establishment->googleMapsUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
        </div>
    </article>

    <div class="card-actions">
        <a href="{{ route('establishments.edit', $establishment) }}" class="button button--outline">Editar</a>
        <form method="POST" action="{{ route('establishments.destroy', $establishment) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="button button--danger">Excluir</button>
        </form>
    </div>
@endsection
