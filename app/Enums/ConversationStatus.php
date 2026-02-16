<?php

namespace App\Enums;

enum ConversationStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case WAITING_ON_CUSTOMER = 'waiting_on_customer';
    case RESOLVED = 'resolved';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::IN_PROGRESS => 'In Progress',
            self::WAITING_ON_CUSTOMER => 'Waiting on Customer',
            self::RESOLVED => 'Resolved',
            self::ARCHIVED => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => 'warning',
            self::IN_PROGRESS => 'info',
            self::WAITING_ON_CUSTOMER => 'gray',
            self::RESOLVED => 'success',
            self::ARCHIVED => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::NEW => 'heroicon-o-sparkles',
            self::IN_PROGRESS => 'heroicon-o-chat-bubble-left-right',
            self::WAITING_ON_CUSTOMER => 'heroicon-o-clock',
            self::RESOLVED => 'heroicon-o-check-circle',
            self::ARCHIVED => 'heroicon-o-archive-box',
        };
    }

    public function isOpen(): bool
    {
        return in_array($this, [self::NEW, self::IN_PROGRESS, self::WAITING_ON_CUSTOMER]);
    }

    public function isClosed(): bool
    {
        return in_array($this, [self::RESOLVED, self::ARCHIVED]);
    }
}
