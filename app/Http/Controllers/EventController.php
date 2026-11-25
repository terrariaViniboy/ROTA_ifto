<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::query()
            ->withCount('establishments')
            ->orderByDesc('start_date')
            ->orderBy('name')
            ->get();

        return view('events.index', [
            'events' => $events,
        ]);
    }

    public function create(): View
    {
        return view('events.create', [
            'event' => new Event,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $event = Event::query()->create($request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes(),
        ));

        return redirect()
            ->route('events.guide', $event)
            ->with('status', 'Evento cadastrado.');
    }

    public function show(Event $event): View
    {
        $event->loadCount('establishments');

        return view('events.show', [
            'event' => $event,
        ]);
    }

    public function guide(Request $request, Event $event): View
    {
        $category = $request->query('category');
        $selectedCategory = is_string($category) && in_array($category, Establishment::CATEGORIES, true)
            ? $category
            : null;

        $availableSubcategories = $selectedCategory !== null
            ? $event->establishments()
                ->where('category', $selectedCategory)
                ->whereNotNull('subcategory')
                ->where('subcategory', '!=', '')
                ->distinct()
                ->orderBy('subcategory')
                ->pluck('subcategory')
            : collect();

        $subcategory = $request->query('subcategory');
        $selectedSubcategory = is_string($subcategory) && $availableSubcategories->contains($subcategory)
            ? $subcategory
            : null;

        $establishments = $event->establishments()
            ->when(
                $selectedCategory,
                fn ($query) => $query->where('category', $selectedCategory),
            )
            ->when(
                $selectedSubcategory,
                fn ($query) => $query->where('subcategory', $selectedSubcategory),
            )
            ->orderBy('subcategory')
            ->orderBy('name')
            ->get();

        $establishments->each->setRelation('event', $event);

        return view('events.guide', [
            'event' => $event,
            'establishments' => $establishments,
            'selectedCategory' => $selectedCategory,
            'availableSubcategories' => $availableSubcategories,
            'selectedSubcategory' => $selectedSubcategory,
        ]);
    }

    public function edit(Event $event): View
    {
        return view('events.edit', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update($request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes(),
        ));

        return redirect()
            ->route('events.guide', $event)
            ->with('status', 'Evento atualizado.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('status', 'Evento excluído.');
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'location_address' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function attributes(): array
    {
        return [
            'name' => 'nome',
            'city' => 'cidade',
            'start_date' => 'data de início',
            'end_date' => 'data de término',
            'location_address' => 'endereço do local',
            'is_active' => 'situação',
        ];
    }
}
