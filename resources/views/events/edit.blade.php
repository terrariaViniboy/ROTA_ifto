@extends('layouts.app')

@section('title', 'Editar evento — Rota IFTO')

@section('content')
    @php
        $isActive = (string) old('is_active', $event->exists ? ($event->is_active ? '1' : '0') : '1');
    @endphp

    <h1 class="mb-6 text-3xl font-semibold">Editar evento</h1>
    <form method="POST" action="{{ route('events.update', $event) }}" class="max-w-2xl rounded border border-stone-200 bg-white p-5">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-medium mb-1">Nome</label>
            <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Cidade</label>
            <input type="text" name="city" value="{{ old('city', $event->city ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Data de início</label>
            <input type="date" name="start_date" value="{{ old('start_date', $event->start_date?->toDateString() ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Data de término</label>
            <input type="date" name="end_date" value="{{ old('end_date', $event->end_date?->toDateString() ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Endereço do local</label>
            <input type="text" name="location_address" value="{{ old('location_address', $event->location_address ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('location_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Situação</label>
            <select name="is_active" class="w-full rounded border px-3 py-2">
                <option value="1" @selected($isActive === '1')>Ativo</option>
                <option value="0" @selected($isActive === '0')>Inativo</option>
            </select>
            @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="rounded bg-emerald-950 px-4 py-2 text-sm font-medium text-white">Atualizar</button>
    </form>
@endsection
