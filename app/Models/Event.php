<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'city',
        'start_date',
        'end_date',
        'location_address',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function establishments(): HasMany
    {
        return $this->hasMany(Establishment::class);
    }
}
