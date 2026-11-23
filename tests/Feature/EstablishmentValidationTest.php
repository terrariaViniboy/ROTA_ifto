<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstablishmentValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_validates_required_rules(): void
    {
        $response = $this->actingAsAdmin()
            ->from(route('establishments.create'))
            ->post(route('establishments.store'), []);

        $response->assertRedirect(route('establishments.create'));
        $response->assertSessionHasErrors([
            'event_id',
            'name',
            'address',
            'code',
            'category',
            'status',
            'group_capacity',
        ]);
        $this->assertDatabaseCount('establishments', 0);
    }

    public function test_store_rejects_unknown_event_category_status_and_non_positive_capacity(): void
    {
        $event = $this->event();

        $response = $this->actingAsAdmin()->post(route('establishments.store'), $this->payload($event, [
            'event_id' => 9999,
            'category' => 'lazer',
            'status' => 'fechado',
            'group_capacity' => 0,
        ]));

        $response->assertSessionHasErrors([
            'event_id',
            'category',
            'status',
            'group_capacity',
        ]);
    }

    public function test_store_rejects_duplicate_code(): void
    {
        $event = $this->event();
        $this->establishment($event, ['code' => 'HOT-01']);

        $response = $this->actingAsAdmin()->post(route('establishments.store'), $this->payload($event, [
            'code' => 'HOT-01',
            'name' => 'Outro Hotel',
        ]));

        $response->assertSessionHasErrors('code');
        $this->assertDatabaseCount('establishments', 1);
    }

    public function test_update_allows_the_same_code_and_rejects_another_establishments_code(): void
    {
        $event = $this->event();
        $current = $this->establishment($event, ['code' => 'HOT-01', 'name' => 'Hotel Atual']);
        $this->establishment($event, ['code' => 'REST-02', 'name' => 'Restaurante']);

        $keepOwnCode = $this->actingAsAdmin()->put(route('establishments.update', $current), $this->payload($event, [
            'code' => 'HOT-01',
            'name' => 'Hotel Atualizado',
        ]));

        $keepOwnCode->assertSessionHasNoErrors();
        $this->assertDatabaseHas('establishments', [
            'id' => $current->id,
            'name' => 'Hotel Atualizado',
            'code' => 'HOT-01',
        ]);

        $takeOtherCode = $this->actingAsAdmin()->put(route('establishments.update', $current), $this->payload($event, [
            'code' => 'REST-02',
        ]));

        $takeOtherCode->assertSessionHasErrors('code');
        $this->assertDatabaseHas('establishments', [
            'id' => $current->id,
            'code' => 'HOT-01',
        ]);
    }

    public function test_store_persists_a_valid_establishment(): void
    {
        $event = $this->event();

        $response = $this->actingAsAdmin()->post(route('establishments.store'), $this->payload($event));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('establishments', [
            'event_id' => $event->id,
            'name' => 'Hotel Palmas',
            'code' => 'HOT-01',
            'category' => 'hospedagem',
            'status' => 'ativo',
            'group_capacity' => 12,
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
     */
    private function establishment(Event $event, array $overrides = []): Establishment
    {
        return Establishment::query()->create(array_merge([
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
        ], $overrides));
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
}
