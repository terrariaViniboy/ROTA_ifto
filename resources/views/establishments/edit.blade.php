@extends('layouts.app')

@section('title', 'Editar estabelecimento — Rota IFTO')

@section('content')
    @php
        $selectedEvent = (string) old('event_id', $establishment->event_id ?? '');
        $selectedCategory = old('category', $establishment->category ?? '');
        $selectedStatus = old('status', $establishment->status ?? \App\Models\Establishment::STATUS_ATIVO);
    @endphp

    <h1 class="mb-6 text-3xl font-semibold">Editar estabelecimento</h1>
    <form method="POST" action="{{ route('establishments.update', $establishment) }}" class="max-w-2xl rounded border border-stone-200 bg-white p-5">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-medium mb-1">Evento</label>
            <select name="event_id" class="w-full rounded border px-3 py-2">
                <option value="">Selecione</option>
                @foreach ($events as $eventOption)
                    <option value="{{ $eventOption->id }}" @selected($selectedEvent === (string) $eventOption->id)>{{ $eventOption->name }}</option>
                @endforeach
            </select>
            @error('event_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Nome</label>
            <input type="text" name="name" value="{{ old('name', $establishment->name ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Código</label>
            <input type="text" name="code" value="{{ old('code', $establishment->code ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Categoria</label>
            <select name="category" class="w-full rounded border px-3 py-2">
                <option value="">Selecione</option>
                @foreach (\App\Models\Establishment::categoryOptions() as $value => $label)
                    <option value="{{ $value }}" @selected($selectedCategory === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Situação</label>
            <select name="status" class="w-full rounded border px-3 py-2">
                @foreach (\App\Models\Establishment::statusOptions() as $value => $label)
                    <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Capacidade do grupo</label>
            <input type="number" name="group_capacity" min="1" step="1" value="{{ old('group_capacity', $establishment->group_capacity ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('group_capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Endereço</label>
            <input type="text" name="address" value="{{ old('address', $establishment->address ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Faixa de preço</label>
            <input type="text" name="price_range" value="{{ old('price_range', $establishment->price_range ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('price_range') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">WhatsApp</label>
            <input type="text" name="phone_whatsapp" value="{{ old('phone_whatsapp', $establishment->phone_whatsapp ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('phone_whatsapp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Link do Google Maps</label>
            <input type="url" name="maps_url" value="{{ old('maps_url', $establishment->maps_url ?? '') }}" class="w-full rounded border px-3 py-2">
            @error('maps_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="rounded bg-emerald-950 px-4 py-2 text-sm font-medium text-white">Atualizar</button>
    </form>
@endsection