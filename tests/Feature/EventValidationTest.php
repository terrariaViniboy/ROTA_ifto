<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_requires_every_event_field(): void
    {
        $response = $this->actingAsAdmin()
            ->from(route('events.create'))
            ->post(route('events.store'), []);

        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors([
            'name',
            'city',
            'start_date',
            'end_date',
            'location_address',
            'is_active',
        ]);
        $this->assertDatabaseCount('events', 0);
    }

    public function test_store_rejects_end_date_before_start_date(): void
    {
        $response = $this->actingAsAdmin()->post(route('events.store'), $this->payload([
            'start_date' => '2026-11-22',
            'end_date' => '2026-11-20',
        ]));

        $response->assertSessionHasErrors('end_date');
        $this->assertDatabaseCount('events', 0);
    }

    public function test_store_accepts_end_date_equal_to_start_date(): void
    {
        $response = $this->actingAsAdmin()->post(route('events.store'), $this->payload([
            'name' => 'Oficina de um dia',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-20',
        ]));

        $response->assertSessionHasNoErrors();

        $event = Event::query()->where('name', 'Oficina de um dia')->firstOrFail();
        $this->assertSame('2026-11-20', $event->start_date->toDateString());
        $this->assertSame('2026-11-20', $event->end_date->toDateString());
    }

    public function test_update_rejects_end_date_before_start_date(): void
    {
        $event = Event::query()->create($this->payload([
            'is_active' => true,
        ]));

        $response = $this->actingAsAdmin()->put(route('events.update', $event), $this->payload([
            'start_date' => '2026-12-10',
            'end_date' => '2026-12-01',
        ]));

        $response->assertSessionHasErrors('end_date');
        $this->assertSame('2026-11-22', $event->fresh()->end_date->toDateString());
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
}
