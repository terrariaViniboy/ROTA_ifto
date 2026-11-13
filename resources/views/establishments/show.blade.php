@extends('layouts.app')

@section('title', $establishment->name.' — Rota IFTO')

@section('content')
    <p class="text-sm">
        <a href="{{ route('events.guide', $establishment->event) }}" class="text-emerald-900 underline-offset-4 hover:underline">Voltar ao guia de {{ $establishment->event->name }}</a>
    </p>

    <article class="mt-3 rounded border border-stone-200 bg-white p-5">
        <p class="text-xs font-medium uppercase tracking-wide text-emerald-900">{{ $establishment->categoryLabel() }}</p>
        <h1 class="mt-1 text-3xl font-semibold">{{ $establishment->name }}</h1>
        <p class="mt-2 text-sm text-stone-700">Código {{ $establishment->code }} · {{ $establishment->statusLabel() }}</p>

        @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
            <p class="mt-3">
                <span class="inline-flex rounded-full bg-amber-400 px-2 py-1 text-xs font-semibold text-amber-950">{{ $establishment->group_capacity }} vagas</span>
            </p>
        @endif

        <dl class="mt-6 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div>
                <dt class="font-medium text-stone-500">Endereço</dt>
                <dd>{{ $establishment->address }}</dd>
            </div>
            <div>
                <dt class="font-medium text-stone-500">Evento</dt>
                <dd>{{ $establishment->event->name }} — {{ $establishment->event->city }}</dd>
            </div>
            <div>
                <dt class="font-medium text-stone-500">Capacidade do grupo</dt>
                <dd>{{ $establishment->group_capacity ?? 'Não informada' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-stone-500">Preço</dt>
                <dd>{{ $establishment->price_range ?: 'Não informado' }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex flex-col gap-2 sm:flex-row">
            @if ($establishment->whatsappUrl())
                <a href="{{ $establishment->whatsappUrl() }}" class="inline-flex flex-1 justify-center rounded bg-emerald-800 px-4 py-2 text-center text-sm font-medium text-white" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
            @else
                <p class="text-sm text-stone-600">WhatsApp não informado.</p>
            @endif
            <a href="{{ $establishment->googleMapsUrl() }}" class="inline-flex flex-1 justify-center rounded border border-emerald-800 px-4 py-2 text-center text-sm font-medium text-emerald-950" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
        </div>
    </article>

    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('establishments.edit', $establishment) }}" class="inline-flex justify-center rounded border border-stone-300 bg-white px-4 py-2 text-sm font-medium">Editar</a>
        <form method="POST" action="{{ route('establishments.destroy', $establishment) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex w-full justify-center rounded border border-red-200 px-4 py-2 text-sm text-red-600 sm:w-auto">Excluir</button>
        </form>
    </div>
@endsection
