@extends('layouts.app')

@section('title', 'Eventos — Rota IFTO')

@section('content')
    <div class="page-head">
        <h1 class="page-title">Eventos</h1>
        @if ($isAdmin)
            <a href="{{ route('events.create') }}" class="button">Novo evento</a>
        @endif
    </div>

    @if ($events->isEmpty())
        <x-empty-state message="Ainda não há eventos cadastrados." />
    @else
        <ul class="card-list">
            @foreach ($events as $event)
                <li class="card card--evento">
                    <h2 class="card-title">
                        <a href="{{ route('events.show', $event) }}">{{ $event->name }}</a>
                    </h2>
                    <p class="muted">{{ $event->city }}</p>
                    <p class="muted">{{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}</p>
                    <p class="muted">{{ $event->is_active ? 'Ativo' : 'Inativo' }} · {{ $event->establishments_count }} estabelecimentos</p>
                    <div class="card-actions">
                        <a href="{{ route('events.guide', $event) }}" class="button button--outline">Guia</a>
                        @if ($isAdmin)
                            <a href="{{ route('events.edit', $event) }}" class="button button--outline">Editar</a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button--danger">Excluir</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
