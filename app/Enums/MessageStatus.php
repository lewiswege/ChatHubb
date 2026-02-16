<?php

namespace App\Enums;

enum MessageStatus: string
{
    case PENDING = 'pending';
    case QUEUED = 'queued';
    case SENT = 'sent';
    case DELIVERED = 'delivered';
    case READ = 'read';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::QUEUED => 'Queued',
            self::SENT => 'Sent',
            self::DELIVERED => 'Delivered',
            self::READ => 'Read',
            self::FAILED => 'Failed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::QUEUED => 'info',
            self::SENT => 'primary',
            self::DELIVERED => 'success',
            self::READ => 'success',
            self::FAILED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::QUEUED => 'heroicon-o-queue-list',
            self::SENT => 'heroicon-o-paper-airplane',
            self::DELIVERED => 'heroicon-o-check',
            self::READ => 'heroicon-o-check-circle',
            self::FAILED => 'heroicon-o-x-circle',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::DELIVERED, self::READ, self::FAILED]);
    }

    public function isSuccess(): bool
    {
        return in_array($this, [self::SENT, self::DELIVERED, self::READ]);
    }
}
