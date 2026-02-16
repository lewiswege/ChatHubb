<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_primary',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function channelIdentifiers(): HasMany
    {
        return $this->hasMany(CustomerChannelIdentifier::class);
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->phone_primary ?: $this->email ?: 'Unknown Customer';
    }
}
