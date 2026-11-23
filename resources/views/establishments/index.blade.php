@extends('layouts.app')

@section('title', 'Estabelecimentos — Rota IFTO')

@section('content')
    <div class="page-head">
        <h1 class="page-title">Estabelecimentos</h1>
        <a href="{{ route('establishments.create') }}" class="button">Novo estabelecimento</a>
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
                                <h2 class="card-title">
                                    <a href="{{ route('establishments.show', $establishment) }}">{{ $establishment->name }}</a>
                                </h2>
                                <p class="muted">{{ $establishment->event->name }}</p>
                                <p class="muted">{{ $establishment->address }}</p>
                                @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
                                    <p class="badge">{{ $establishment->group_capacity }} vagas</p>
                                @endif
                                <div class="card-actions">
                                    @if ($establishment->whatsappUrl())
                                        <a href="{{ $establishment->whatsappUrl() }}" class="button" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
                                    @endif
                                    <a href="{{ $establishment->googleMapsUrl() }}" class="button button--outline" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
                                    <a href="{{ route('establishments.edit', $establishment) }}" class="button button--outline">Editar</a>
                                    <form method="POST" action="{{ route('establishments.destroy', $establishment) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button--danger">Excluir</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </details>
            @endif
        @endforeach
    @endif
@endsection
