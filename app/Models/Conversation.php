<?php

namespace App\Models;

use App\Enums\ChannelType;
use App\Enums\ConversationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'customer_id',
        'status',
        'assigned_agent_id',
        'last_message_at',
        'last_inbound_channel',
        'unread_count',
        'resolved_at',
    ];

    protected $casts = [
        'status' => ConversationStatus::class,
        'last_inbound_channel' => ChannelType::class,
        'last_message_at' => 'datetime',
        'resolved_at' => 'datetime',
        'unread_count' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(InternalNote::class)->orderBy('created_at');
    }

    public function scopeUnread($query)
    {
        return $query->where('unread_count', '>', 0);
    }

    public function scopeAssignedTo($query, $agentId)
    {
        return $query->where('assigned_agent_id', $agentId);
    }

    public function scopeByStatus($query, ConversationStatus $status)
    {
        return $query->where('status', $status);
    }

    public function markAsRead(): void
    {
        $this->update(['unread_count' => 0]);
    }

    public function incrementUnread(): void
    {
        $this->increment('unread_count');
    }
}
