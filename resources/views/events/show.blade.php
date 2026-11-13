@extends('layouts.app')

@section('title', $event->name.' — Rota IFTO')

@section('content')
    <h1 class="text-3xl font-semibold">{{ $event->name }}</h1>
    <dl class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
        <div>
            <dt class="font-medium text-stone-500">Cidade</dt>
            <dd>{{ $event->city }}</dd>
        </div>
        <div>
            <dt class="font-medium text-stone-500">Período</dt>
            <dd>{{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}</dd>
        </div>
        <div>
            <dt class="font-medium text-stone-500">Local</dt>
            <dd>{{ $event->location_address }}</dd>
        </div>
        <div>
            <dt class="font-medium text-stone-500">Situação</dt>
            <dd>{{ $event->is_active ? 'Ativo' : 'Inativo' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-stone-500">Estabelecimentos</dt>
            <dd>{{ $event->establishments_count }}</dd>
        </div>
    </dl>
    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('events.guide', $event) }}" class="inline-flex justify-center rounded bg-emerald-950 px-4 py-2 text-sm font-medium text-white">Abrir guia</a>
        <a href="{{ route('events.edit', $event) }}" class="inline-flex justify-center rounded border border-stone-300 bg-white px-4 py-2 text-sm font-medium">Editar</a>
        <form method="POST" action="{{ route('events.destroy', $event) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex w-full justify-center rounded border border-red-200 px-4 py-2 text-sm text-red-600 sm:w-auto">Excluir</button>
        </form>
    </div>
@endsection
