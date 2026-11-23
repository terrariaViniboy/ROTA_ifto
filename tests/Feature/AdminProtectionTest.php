<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_event_create_form(): void
    {
        $this->get(route('events.create'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_store_an_event(): void
    {
        $this->post(route('events.store'), [
            'name' => 'Evento Intruso',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus',
            'is_active' => '1',
        ])->assertRedirect(route('admin.login'));

        $this->assertDatabaseMissing('events', [
            'name' => 'Evento Intruso',
        ]);
    }

    public function test_guest_cannot_delete_an_event(): void
    {
        $event = Event::query()->create([
            'name' => 'Evento Protegido',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus',
            'is_active' => true,
        ]);

        $this->delete(route('events.destroy', $event))
            ->assertRedirect(route('admin.login'));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Evento Protegido',
        ]);
    }

    public function test_guest_cannot_store_an_establishment(): void
    {
        $event = Event::query()->create([
            'name' => 'Evento Base',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus',
            'is_active' => true,
        ]);

        $this->post(route('establishments.store'), [
            'event_id' => $event->id,
            'name' => 'Hotel Intruso',
            'code' => 'HACK-1',
            'category' => Establishment::CATEGORY_HOSPEDAGEM,
            'group_capacity' => 10,
            'address' => 'Centro',
            'status' => Establishment::STATUS_ATIVO,
        ])->assertRedirect(route('admin.login'));

        $this->assertDatabaseMissing('establishments', [
            'code' => 'HACK-1',
        ]);
    }

    public function test_admin_can_open_event_create_form(): void
    {
        $this->actingAsAdmin()
            ->get(route('events.create'))
            ->assertOk();
    }
}
