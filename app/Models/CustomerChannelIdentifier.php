<?php

namespace App\Models;

use App\Enums\ChannelType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerChannelIdentifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'channel',
        'identifier',
        'verified_at',
        'metadata',
    ];

    protected $casts = [
        'channel' => ChannelType::class,
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
