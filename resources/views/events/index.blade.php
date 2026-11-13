@extends('layouts.app')

@section('title', 'Eventos — Rota IFTO')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-semibold">Eventos</h1>
        <a href="{{ route('events.create') }}" class="inline-flex justify-center rounded bg-emerald-950 px-4 py-2 text-sm font-medium text-white">Novo evento</a>
    </div>

    @if ($events->isEmpty())
        <p class="rounded border border-dashed border-stone-300 bg-white px-4 py-8 text-center text-stone-600">Nenhum evento cadastrado.</p>
    @else
        <ul class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($events as $event)
                <li class="rounded border border-stone-200 bg-white p-5">
                    <h2 class="text-xl font-semibold">
                        <a href="{{ route('events.show', $event) }}" class="underline-offset-4 hover:underline">{{ $event->name }}</a>
                    </h2>
                    <p class="mt-2 text-sm text-stone-700">{{ $event->city }}</p>
                    <p class="mt-1 text-sm text-stone-700">{{ $event->start_date->format('d/m/Y') }} a {{ $event->end_date->format('d/m/Y') }}</p>
                    <p class="mt-3 text-sm">{{ $event->is_active ? 'Ativo' : 'Inativo' }} · {{ $event->establishments_count }} estabelecimentos</p>
                    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                        <a href="{{ route('events.guide', $event) }}" class="text-sm font-medium text-emerald-900 underline-offset-4 hover:underline">Guia</a>
                        <a href="{{ route('events.edit', $event) }}" class="text-sm font-medium text-emerald-900 underline-offset-4 hover:underline">Editar</a>
                        <form method="POST" action="{{ route('events.destroy', $event) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600">Excluir</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
