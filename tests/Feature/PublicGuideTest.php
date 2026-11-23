<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicGuideTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_lists_only_active_events(): void
    {
        $active = $this->event(['name' => 'Jogos Internos 2026', 'is_active' => true]);
        $inactive = $this->event(['name' => 'Feira Encerrada', 'is_active' => false]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Jogos Internos 2026');
        $response->assertDontSee('Feira Encerrada');
        $response->assertSee(route('events.guide', $active), false);
        $response->assertDontSee(route('events.guide', $inactive), false);
    }

    public function test_event_guide_filters_establishments_by_category_query_string(): void
    {
        $event = $this->event(['name' => 'Semana de Ciência']);
        $otherEvent = $this->event(['name' => 'Outro Evento']);

        $hotel = $this->establishment($event, [
            'name' => 'Hotel Palmas',
            'category' => Establishment::CATEGORY_HOSPEDAGEM,
        ]);
        $this->establishment($event, [
            'name' => 'Restaurante Centro',
            'code' => 'REST-01',
            'category' => Establishment::CATEGORY_ALIMENTACAO,
        ]);
        $this->establishment($otherEvent, [
            'name' => 'Hotel de Outro Evento',
            'code' => 'HOT-99',
            'category' => Establishment::CATEGORY_HOSPEDAGEM,
        ]);

        $response = $this->get('/eventos/'.$event->id.'/guia?category=hospedagem');

        $response->assertOk();
        $response->assertSee('Semana de Ciência');
        $response->assertSee('Hotel Palmas');
        $response->assertSee(route('establishments.show', $hotel), false);
        $response->assertDontSee('Restaurante Centro');
        $response->assertDontSee('Hotel de Outro Evento');
        $response->assertSee('/eventos/'.$event->id.'/guia?category=alimentacao', false);
    }

    public function test_event_guide_ignores_unknown_category_and_lists_linked_establishments(): void
    {
        $event = $this->event();
        $this->establishment($event, ['name' => 'Van Campus', 'category' => Establishment::CATEGORY_TRANSPORTE]);
        $this->establishment($event, [
            'name' => 'Posto Médico',
            'code' => 'EMG-01',
            'category' => Establishment::CATEGORY_EMERGENCIA,
        ]);

        $response = $this->get('/eventos/'.$event->id.'/guia?category=lazer');

        $response->assertOk();
        $response->assertSee('Van Campus');
        $response->assertSee('Posto Médico');
    }

    public function test_guide_cards_show_quick_actions_lodging_badge_and_emergency_filter(): void
    {
        $event = $this->event(['name' => 'Mostra Cultural']);
        $hotel = $this->establishment($event, [
            'name' => 'Hotel Palmas',
            'category' => Establishment::CATEGORY_HOSPEDAGEM,
            'group_capacity' => 18,
            'phone_whatsapp' => '63911112222',
            'address' => 'Quadra 104 Sul',
        ]);

        $response = $this->get(route('events.guide', $event));

        $response->assertOk();
        $response->assertSee('18 vagas');
        $response->assertSee('Chamar no WhatsApp');
        $response->assertSee('Abrir Rota no Maps');
        $response->assertSee('https://wa.me/5563911112222', false);
        $response->assertSee(htmlspecialchars($hotel->googleMapsUrl(), ENT_QUOTES), false);
        $response->assertSee('pill--alert', false);
        $response->assertSee('/eventos/'.$event->id.'/guia?category=emergencia', false);
    }

    public function test_establishment_details_show_capacity_price_and_generated_links(): void
    {
        $event = $this->event();
        $establishment = $this->establishment($event, [
            'name' => 'Pousada IFTO',
            'group_capacity' => 40,
            'price_range' => 'R$ 80 a R$ 120',
            'phone_whatsapp' => '(63) 98400-1122',
            'maps_url' => null,
            'address' => 'Avenida Teotônio Segurado, Palmas',
        ]);

        $response = $this->get(route('establishments.show', $establishment));

        $response->assertOk();
        $response->assertSee('Pousada IFTO');
        $response->assertSee('40');
        $response->assertSee('R$ 80 a R$ 120');
        $response->assertSee('https://wa.me/5563984001122', false);
        $response->assertSee(
            'https://www.google.com/maps/search/?api=1&amp;query=Avenida%20Teot%C3%B4nio%20Segurado%2C%20Palmas',
            false,
        );
    }

    public function test_establishment_details_keep_existing_google_maps_url(): void
    {
        $event = $this->event();
        $establishment = $this->establishment($event, [
            'phone_whatsapp' => '5563999990000',
            'maps_url' => 'https://maps.google.com/?q=campus-palmas',
        ]);

        $response = $this->get(route('establishments.show', $establishment));

        $response->assertOk();
        $response->assertSee('https://wa.me/5563999990000', false);
        $response->assertSee('https://maps.google.com/?q=campus-palmas', false);
    }

    private function event(array $overrides = []): Event
    {
        return Event::query()->create(array_merge([
            'name' => 'JIFTO 2026',
            'city' => 'Palmas',
            'start_date' => '2026-11-20',
            'end_date' => '2026-11-22',
            'location_address' => 'Campus Palmas',
            'is_active' => true,
        ], $overrides));
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
            'group_capacity' => 20,
            'price_range' => 'R$ 100',
            'phone_whatsapp' => '63999990000',
            'maps_url' => null,
            'address' => 'Quadra 104 Sul',
            'status' => Establishment::STATUS_ATIVO,
        ], $overrides));
    }
}
