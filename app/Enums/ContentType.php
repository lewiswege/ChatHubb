<?php

namespace App\Enums;

enum ContentType: string
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case DOCUMENT = 'document';
    case AUDIO = 'audio';
    case VIDEO = 'video';
    case LOCATION = 'location';
    case TEMPLATE = 'template';

    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'Text',
            self::IMAGE => 'Image',
            self::DOCUMENT => 'Document',
            self::AUDIO => 'Audio',
            self::VIDEO => 'Video',
            self::LOCATION => 'Location',
            self::TEMPLATE => 'Template',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::TEXT => 'heroicon-o-chat-bubble-left',
            self::IMAGE => 'heroicon-o-photo',
            self::DOCUMENT => 'heroicon-o-document',
            self::AUDIO => 'heroicon-o-microphone',
            self::VIDEO => 'heroicon-o-video-camera',
            self::LOCATION => 'heroicon-o-map-pin',
            self::TEMPLATE => 'heroicon-o-document-text',
        };
    }

    public function requiresAttachment(): bool
    {
        return in_array($this, [self::IMAGE, self::DOCUMENT, self::AUDIO, self::VIDEO]);
    }
}
