<?php

namespace App\Channels\ValueObjects;

readonly class ChannelResponse
{
    public function __construct(
        public bool $success,
        public ?string $externalMessageId = null,
        public ?string $error = null,
        public array $metadata = [],
    ) {}

    public static function success(string $externalMessageId, array $metadata = []): self
    {
        return new self(
            success: true,
            externalMessageId: $externalMessageId,
            metadata: $metadata
        );
    }

    public static function failed(string $error, array $metadata = []): self
    {
        return new self(
            success: false,
            error: $error,
            metadata: $metadata
        );
    }
}
