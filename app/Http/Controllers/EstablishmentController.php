<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    public function index(): View
    {
        $establishments = Establishment::query()
            ->with('event')
            ->orderBy('name')
            ->get();

        return view('establishments.index', [
            'establishments' => $establishments,
        ]);
    }

    public function create(): View
    {
        return view('establishments.create', [
            'establishment' => new Establishment,
            'events' => $this->events(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $establishment = Establishment::query()->create($this->validated($request));

        return redirect()
            ->route('establishments.show', $establishment)
            ->with('status', 'Estabelecimento cadastrado.');
    }

    public function show(Establishment $establishment): View
    {
        $establishment->load('event');

        return view('establishments.show', [
            'establishment' => $establishment,
        ]);
    }

    public function edit(Establishment $establishment): View
    {
        return view('establishments.edit', [
            'establishment' => $establishment,
            'events' => $this->events(),
        ]);
    }

    public function update(Request $request, Establishment $establishment): RedirectResponse
    {
        $establishment->update($this->validated($request, $establishment));

        return redirect()
            ->route('establishments.show', $establishment)
            ->with('status', 'Estabelecimento atualizado.');
    }

    public function destroy(Establishment $establishment): RedirectResponse
    {
        $establishment->delete();

        return redirect()
            ->route('establishments.index')
            ->with('status', 'Estabelecimento excluído.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Establishment $establishment = null): array
    {
        $code = Rule::unique('establishments', 'code');

        if ($establishment !== null) {
            $code->ignore($establishment->id);
        }

        return $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', $code],
            'category' => ['required', 'in:hospedagem,alimentacao,transporte,emergencia'],
            'subcategory' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:ativo,lotado,inativo'],
            'group_capacity' => ['required', 'integer', 'min:1'],
            'price_range' => ['nullable', 'string', 'max:255'],
            'phone_whatsapp' => ['nullable', 'string', 'max:30'],
            'maps_url' => ['nullable', 'url', 'max:2048'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'android_app_url' => ['nullable', 'url', 'max:2048'],
            'ios_app_url' => ['nullable', 'url', 'max:2048'],
        ], [
            'group_capacity.min' => 'A capacidade do grupo deve ser um inteiro positivo.',
            'code.unique' => 'Este código já está em uso.',
        ], [
            'event_id' => 'evento',
            'name' => 'nome',
            'address' => 'endereço',
            'code' => 'código',
            'category' => 'categoria',
            'subcategory' => 'subcategoria',
            'status' => 'situação',
            'group_capacity' => 'capacidade do grupo',
            'price_range' => 'faixa de preço',
            'phone_whatsapp' => 'WhatsApp',
            'maps_url' => 'link do Google Maps',
            'instagram_url' => 'Instagram',
            'android_app_url' => 'link do Google Play',
            'ios_app_url' => 'link da App Store',
        ]);
    }

    /**
     * @return Collection<int, Event>
     */
    private function events()
    {
        return Event::query()->orderBy('name')->get();
    }
}
