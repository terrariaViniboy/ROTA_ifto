<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstablishmentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_establishments(): void
    {
        $event = $this->event();
        $establishment = Establishment::query()->create($this->record($event));

        $response = $this->get(route('establishments.index'));

        $response->assertOk();
        $response->assertSee($establishment->name);
    }

    public function test_store_persists_a_valid_establishment_and_redirects(): void
    {
        $event = $this->event();

        $response = $this->post(route('establishments.store'), $this->payload($event));

        $response->assertStatus(302);
        $this->assertDatabaseHas('establishments', [
            'event_id' => $event->id,
            'name' => 'Hotel Palmas',
            'code' => 'HOT-01',
            'category' => 'hospedagem',
            'status' => 'ativo',
            'group_capacity' => 12,
            'address' => 'Quadra 104 Sul',
        ]);

        $establishment = Establishment::query()->where('code', 'HOT-01')->firstOrFail();
        $response->assertRedirect(route('establishments.show', $establishment));
    }

    public function test_update_persists_changes_and_redirects(): void
    {
        $event = $this->event();
        $establishment = Establishment::query()->create($this->record($event));

        $response = $this->put(route('establishments.update', $establishment), $this->payload($event, [
            'name' => 'Hotel Atualizado',
            'code' => 'HOT-01',
            'status' => 'lotado',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('establishments.show', $establishment));
        $this->assertDatabaseHas('establishments', [
            'id' => $establishment->id,
            'name' => 'Hotel Atualizado',
            'code' => 'HOT-01',
            'status' => 'lotado',
        ]);
        $this->assertDatabaseMissing('establishments', [
            'id' => $establishment->id,
            'name' => 'Hotel Palmas',
        ]);
    }

    public function test_destroy_removes_the_establishment_and_redirects(): void
    {
        $event = $this->event();
        $establishment = Establishment::query()->create($this->record($event));

        $response = $this->delete(route('establishments.destroy', $establishment));

        $response->assertStatus(302);
        $response->assertRedirect(route('establishments.index'));
        $this->assertDatabaseMissing('establishments', [
            'id' => $establishment->id,
            'code' => 'HOT-01',
        ]);
    }

    public function test_store_rejects_an_empty_payload(): void
    {
        $response = $this->post(route('establishments.store'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'code', 'category', 'status']);
        $this->assertDatabaseCount('establishments', 0);
    }

    public function test_store_rejects_a_duplicate_code(): void
    {
        $event = $this->event();
        Establishment::query()->create($this->record($event, ['code' => 'HOT-01']));

        $response = $this->post(route('establishments.store'), $this->payload($event, [
            'name' => 'Outro Hotel',
            'code' => 'HOT-01',
        ]));

        $response->assertSessionHasErrors('code');
        $this->assertDatabaseMissing('establishments', [
            'name' => 'Outro Hotel',
        ]);
        $this->assertDatabaseCount('establishments', 1);
    }

    public function test_store_rejects_a_category_and_status_outside_the_allowed_lists(): void
    {
        $event = $this->event();

        $response = $this->post(route('establishments.store'), $this->payload($event, [
            'name' => 'Local Inválido',
            'code' => 'INV-01',
            'category' => 'lazer',
            'status' => 'fechado',
        ]));

        $response->assertSessionHasErrors(['category', 'status']);
        $this->assertDatabaseMissing('establishments', [
            'code' => 'INV-01',
            'name' => 'Local Inválido',
        ]);
    }

    private function event(): Event
    {
        return Event::query()->create([
            'name' => 'JIFTO 2026',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus Palmas',
            'is_active' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(Event $event, array $overrides = []): array
    {
        return array_merge([
            'event_id' => $event->id,
            'name' => 'Hotel Palmas',
            'code' => 'HOT-01',
            'category' => 'hospedagem',
            'group_capacity' => 12,
            'price_range' => 'R$ 100',
            'phone_whatsapp' => '63999990000',
            'maps_url' => 'https://maps.google.com/?q=hotel',
            'address' => 'Quadra 104 Sul',
            'status' => 'ativo',
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function record(Event $event, array $overrides = []): array
    {
        return array_merge([
            'event_id' => $event->id,
            'name' => 'Hotel Palmas',
            'code' => 'HOT-01',
            'category' => Establishment::CATEGORY_HOSPEDAGEM,
            'group_capacity' => 12,
            'price_range' => 'R$ 100',
            'phone_whatsapp' => '63999990000',
            'maps_url' => null,
            'address' => 'Quadra 104 Sul',
            'status' => Establishment::STATUS_ATIVO,
        ], $overrides);
    }
}
