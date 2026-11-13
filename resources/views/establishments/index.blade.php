@extends('layouts.app')

@section('title', 'Estabelecimentos — Rota IFTO')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-semibold">Estabelecimentos</h1>
        <a href="{{ route('establishments.create') }}" class="inline-flex justify-center rounded bg-emerald-950 px-4 py-2 text-sm font-medium text-white">Novo estabelecimento</a>
    </div>

    @if ($establishments->isEmpty())
        <p class="rounded border border-dashed border-stone-300 bg-white px-4 py-8 text-center text-stone-600">Nenhum estabelecimento cadastrado.</p>
    @else
        <ul class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($establishments as $establishment)
                <li class="flex flex-col rounded border border-stone-200 bg-white p-5">
                    <p class="text-xs font-medium uppercase tracking-wide text-emerald-900">{{ $establishment->categoryLabel() }}</p>
                    <h2 class="mt-1 text-xl font-semibold">
                        <a href="{{ route('establishments.show', $establishment) }}" class="underline-offset-4 hover:underline">{{ $establishment->name }}</a>
                    </h2>
                    <p class="mt-1 text-sm text-stone-700">{{ $establishment->event->name }}</p>
                    <p class="mt-1 text-sm">{{ $establishment->address }}</p>
                    @if ($establishment->category === \App\Models\Establishment::CATEGORY_HOSPEDAGEM)
                        <p class="mt-3">
                            <span class="inline-flex rounded-full bg-amber-400 px-2 py-1 text-xs font-semibold text-amber-950">{{ $establishment->group_capacity }} vagas</span>
                        </p>
                    @endif
                    <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                        @if ($establishment->whatsappUrl())
                            <a href="{{ $establishment->whatsappUrl() }}" class="inline-flex flex-1 justify-center rounded bg-emerald-800 px-3 py-2 text-center text-sm font-medium text-white" target="_blank" rel="noopener noreferrer">Chamar no WhatsApp</a>
                        @endif
                        <a href="{{ $establishment->googleMapsUrl() }}" class="inline-flex flex-1 justify-center rounded border border-emerald-800 px-3 py-2 text-center text-sm font-medium text-emerald-950" target="_blank" rel="noopener noreferrer">Abrir Rota no Maps</a>
                    </div>
                    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                        <a href="{{ route('establishments.edit', $establishment) }}" class="text-sm font-medium text-emerald-900 underline-offset-4 hover:underline">Editar</a>
                        <form method="POST" action="{{ route('establishments.destroy', $establishment) }}">
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
