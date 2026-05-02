<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAvailability extends Model
{
    use HasFactory;

    protected $table = 'provider_availabilities';

    protected $fillable = [
        'day',
        'start_at',
        'end_at',
        'provider_id',
    ];

    protected function casts(): array
    {
        return [
            'day' => 'string',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
