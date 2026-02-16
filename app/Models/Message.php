<?php

namespace App\Models;

use App\Enums\ChannelType;
use App\Enums\ContentType;
use App\Enums\MessageDirection;
use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'conversation_id',
        'direction',
        'channel',
        'channel_message_id',
        'reply_to_message_id',
        'content_type',
        'content',
        'metadata',
        'status',
        'status_updated_at',
        'failure_reason',
        'sent_by_agent_id',
    ];

    protected $casts = [
        'direction' => MessageDirection::class,
        'channel' => ChannelType::class,
        'content_type' => ContentType::class,
        'status' => MessageStatus::class,
        'metadata' => 'array',
        'status_updated_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to_message_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function sentByAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_agent_id');
    }

    public function scopeInbound($query)
    {
        return $query->where('direction', MessageDirection::INBOUND);
    }

    public function scopeOutbound($query)
    {
        return $query->where('direction', MessageDirection::OUTBOUND);
    }

    public function scopeByChannel($query, ChannelType $channel)
    {
        return $query->where('channel', $channel);
    }

    public function updateStatus(MessageStatus $status, ?string $failureReason = null): void
    {
        $this->update([
            'status' => $status,
            'status_updated_at' => now(),
            'failure_reason' => $failureReason,
        ]);
    }
}
