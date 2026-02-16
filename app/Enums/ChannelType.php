<?php

namespace App\Enums;

enum ChannelType: string
{
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case WAHA = 'waha';
    case GOOGLE_BUSINESS = 'google_business';
    case TWITTER = 'twitter';
    case TIKTOK = 'tiktok';
    case GOIP = 'goip';
    case SIMULATOR = 'simulator';

    public function label(): string
    {
        return match ($this) {
            self::TELEGRAM => 'Telegram',
            self::WHATSAPP => 'WhatsApp (Official)',
            self::WAHA => 'WhatsApp (Unofficial)',
            self::GOOGLE_BUSINESS => 'Google Business Profile',
            self::TWITTER => 'Twitter/X',
            self::TIKTOK => 'TikTok',
            self::GOIP => 'SMS (GOIP)',
            self::SIMULATOR => 'Simulator',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::TELEGRAM => 'heroicon-o-paper-airplane',
            self::WHATSAPP => 'heroicon-o-chat-bubble-left-right',
            self::WAHA => 'heroicon-o-chat-bubble-left-right',
            self::GOOGLE_BUSINESS => 'heroicon-o-building-storefront',
            self::TWITTER => 'heroicon-o-at-symbol',
            self::TIKTOK => 'heroicon-o-musical-note',
            self::GOIP => 'heroicon-o-device-phone-mobile',
            self::SIMULATOR => 'heroicon-o-beaker',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TELEGRAM => 'info',
            self::WHATSAPP => 'success',
            self::WAHA => 'success',
            self::GOOGLE_BUSINESS => 'warning',
            self::TWITTER => 'gray',
            self::TIKTOK => 'danger',
            self::GOIP => 'gray',
            self::SIMULATOR => 'pink',  // Vibrant pink
        };
    }
}
