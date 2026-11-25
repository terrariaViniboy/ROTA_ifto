<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Establishment extends Model
{
    public const CATEGORY_HOSPEDAGEM = 'hospedagem';

    public const CATEGORY_ALIMENTACAO = 'alimentacao';

    public const CATEGORY_TRANSPORTE = 'transporte';

    public const CATEGORY_EMERGENCIA = 'emergencia';

    /**
     * @var list<string>
     */
    public const CATEGORIES = [
        self::CATEGORY_HOSPEDAGEM,
        self::CATEGORY_ALIMENTACAO,
        self::CATEGORY_TRANSPORTE,
        self::CATEGORY_EMERGENCIA,
    ];

    public const STATUS_ATIVO = 'ativo';

    public const STATUS_LOTADO = 'lotado';

    public const STATUS_INATIVO = 'inativo';

    /**
     * @var list<string>
     */
    public const STATUSES = [
        self::STATUS_ATIVO,
        self::STATUS_LOTADO,
        self::STATUS_INATIVO,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'event_id',
        'name',
        'code',
        'category',
        'subcategory',
        'group_capacity',
        'price_range',
        'phone_whatsapp',
        'maps_url',
        'instagram_url',
        'android_app_url',
        'ios_app_url',
        'address',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'group_capacity' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return array<string, string>
     */
    public static function categoryOptions(): array
    {
        return [
            self::CATEGORY_HOSPEDAGEM => 'Hospedagem',
            self::CATEGORY_ALIMENTACAO => 'Alimentação',
            self::CATEGORY_TRANSPORTE => 'Transporte',
            self::CATEGORY_EMERGENCIA => 'Emergência',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_ATIVO => 'Ativo',
            self::STATUS_LOTADO => 'Lotado',
            self::STATUS_INATIVO => 'Inativo',
        ];
    }

    public function categoryLabel(): string
    {
        return self::categoryOptions()[$this->category] ?? $this->category;
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function whatsappUrl(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone_whatsapp);

        if (! is_string($digits) || $digits === '') {
            return null;
        }

        if (! str_starts_with($digits, '55')) {
            $digits = '55'.$digits;
        }

        return 'https://wa.me/'.$digits;
    }

    public function googleMapsUrl(): string
    {
        $stored = $this->maps_url;

        if (is_string($stored) && preg_match('#\Ahttps?://#i', $stored) === 1) {
            return $stored;
        }

        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode((string) $this->address);
    }

    public function googleMapsEmbedUrl(): string
    {
        $city = $this->relationLoaded('event') ? trim((string) $this->event?->city) : '';
        $address = trim((string) $this->address);

        if (is_string($this->maps_url) && $this->maps_url !== '') {
            $queryString = (string) parse_url($this->maps_url, PHP_URL_QUERY);
            parse_str($queryString, $params);
            $directQuery = $params['query'] ?? $params['q'] ?? null;

            if (is_string($directQuery) && trim($directQuery) !== '') {
                return 'https://maps.google.com/maps?q='.rawurlencode(trim($directQuery)).'&hl=pt-BR&z=17&output=embed';
            }
        }

        if (preg_match('/\A([23456789CFGHJMPQRVWX]{4,8}\+[23456789CFGHJMPQRVWX]{2,3})\b/i', $address, $matches) === 1) {
            $plusQuery = trim($matches[1].' '.($city !== '' ? $city : 'Paraíso do Tocantins - TO'));

            return 'https://maps.google.com/maps?q='.rawurlencode($plusQuery).'&hl=pt-BR&z=17&output=embed';
        }

        $cleanName = trim((string) preg_replace('/\s*(?:—|-)\s*Loja\s*\d+.*|\s*\([^)]*\)/iu', '', (string) $this->name));
        $parts = array_filter([
            $cleanName,
            $address,
        ]);

        $joined = implode(', ', $parts);

        if ($city !== '' && stripos($joined, $city) === false) {
            $joined = $joined !== '' ? $joined.', '.$city : $city;
        }

        return 'https://maps.google.com/maps?q='.rawurlencode($joined).'&hl=pt-BR&z=16&output=embed';
    }

    public function instagramUrl(): ?string
    {
        $raw = trim((string) $this->instagram_url);

        if ($raw === '') {
            return null;
        }

        if (preg_match('#\Ahttps?://#i', $raw) === 1) {
            return $raw;
        }

        $handle = ltrim($raw, '@/');
        $handle = preg_replace('/[^A-Za-z0-9._]/', '', $handle) ?? '';

        if ($handle === '') {
            return null;
        }

        return 'https://www.instagram.com/'.$handle;
    }

    public function instagramHandle(): ?string
    {
        $url = $this->instagramUrl();

        if ($url === null) {
            return null;
        }

        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $segment = explode('/', $path)[0] ?? '';

        return $segment !== '' ? '@'.$segment : null;
    }

    public function isMobilityApp(): bool
    {
        return filled($this->android_app_url) || filled($this->ios_app_url);
    }

    /**
     * @return list<string>
     */
    public static function subcategorySuggestions(): array
    {
        return [
            'Restaurantes',
            'Pizzaria',
            'Hamburgueria',
            'Açaiteria',
            'Sorveteria',
            'Hotel',
            'Pousada',
            'App de Mobilidade',
            'Táxi',
            'Mototáxi',
            'Terminal Rodoviário',
            'Hospital 24h',
            'Unidade de Saúde',
            'Farmácia',
        ];
    }
}
