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
        'group_capacity',
        'price_range',
        'phone_whatsapp',
        'maps_url',
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
}
