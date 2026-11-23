<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_events(): void
    {
        $event = Event::query()->create($this->record());

        $response = $this->get(route('events.index'));

        $response->assertOk();
        $response->assertSee($event->name);
    }

    public function test_store_persists_a_valid_event_and_redirects(): void
    {
        $response = $this->actingAsAdmin()->post(route('events.store'), $this->payload());

        $response->assertStatus(302);
        $this->assertDatabaseHas('events', [
            'name' => 'JIFTO 2026',
            'city' => 'Palmas',
            'start_date' => '2026-11-20 00:00:00',
            'end_date' => '2026-11-22 00:00:00',
            'location_address' => 'Campus Palmas',
            'is_active' => 1,
        ]);

        $event = Event::query()->where('name', 'JIFTO 2026')->firstOrFail();
        $response->assertRedirect(route('events.guide', $event));
    }

    public function test_update_persists_changes_and_redirects(): void
    {
        $event = Event::query()->create($this->record());

        $response = $this->actingAsAdmin()->put(route('events.update', $event), $this->payload([
            'name' => 'JIFTO Atualizado',
            'city' => 'Paraíso',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.guide', $event));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'JIFTO Atualizado',
            'city' => 'Paraíso',
        ]);
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'name' => 'JIFTO 2026',
        ]);
    }

    public function test_destroy_removes_the_event_and_redirects(): void
    {
        $event = Event::query()->create($this->record());

        $response = $this->actingAsAdmin()->delete(route('events.destroy', $event));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'name' => 'JIFTO 2026',
        ]);
    }

    public function test_guide_filters_establishments_by_each_category(): void
    {
        $event = Event::query()->create($this->record());
        $names = [
            Establishment::CATEGORY_HOSPEDAGEM => 'Hotel Palmas',
            Establishment::CATEGORY_ALIMENTACAO => 'Restaurante Centro',
            Establishment::CATEGORY_TRANSPORTE => 'Van Campus',
            Establishment::CATEGORY_EMERGENCIA => 'Posto Médico',
        ];

        foreach ($names as $category => $name) {
            Establishment::query()->create([
                'event_id' => $event->id,
                'name' => $name,
                'code' => strtoupper($category),
                'category' => $category,
                'group_capacity' => 10,
                'address' => 'Campus Palmas',
                'status' => Establishment::STATUS_ATIVO,
            ]);
        }

        foreach ($names as $category => $name) {
            $response = $this->get(route('events.guide', $event).'?category='.$category);

            $response->assertOk();
            $response->assertSee($name);
            foreach ($names as $otherCategory => $otherName) {
                if ($otherCategory !== $category) {
                    $response->assertDontSee($otherName);
                }
            }
        }
    }

    public function test_store_rejects_an_empty_payload(): void
    {
        $response = $this->actingAsAdmin()->post(route('events.store'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'city', 'start_date', 'end_date', 'location_address', 'is_active']);
        $this->assertDatabaseMissing('events', [
            'name' => 'JIFTO 2026',
        ]);
        $this->assertDatabaseCount('events', 0);
    }

    public function test_store_rejects_an_end_date_before_the_start_date(): void
    {
        $response = $this->actingAsAdmin()->post(route('events.store'), $this->payload([
            'start_date' => '2026-11-22',
            'end_date' => '2026-11-20',
        ]));

        $response->assertSessionHasErrors('end_date');
        $this->assertDatabaseMissing('events', [
            'name' => 'JIFTO 2026',
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'JIFTO 2026',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus Palmas',
            'is_active' => '1',
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function record(array $overrides = []): array
    {
        return array_merge([
            'name' => 'JIFTO 2026',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus Palmas',
            'is_active' => true,
        ], $overrides);
    }
}
