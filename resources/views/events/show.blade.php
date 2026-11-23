@extends('layouts.app')

@section('title', $event->name.' — Rota IFTO')

@section('content')
    <article class="card card--evento">
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
            <div>
                <dt>Situação</dt>
                <dd>{{ $event->is_active ? 'Ativo' : 'Inativo' }}</dd>
            </div>
            <div>
                <dt>Estabelecimentos</dt>
                <dd>{{ $event->establishments_count }}</dd>
            </div>
        </dl>
        <div class="card-actions">
            <a href="{{ route('events.guide', $event) }}" class="button">Abrir guia</a>
            @if ($isAdmin)
                <a href="{{ route('events.edit', $event) }}" class="button button--outline">Editar</a>
                <form method="POST" action="{{ route('events.destroy', $event) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button button--danger">Excluir</button>
                </form>
            @endif
        </div>
    </article>
@endsection
