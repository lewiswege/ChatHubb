<?php

namespace App\Channels\ValueObjects;

readonly class InboundMessage
{
    public function __construct(
        public string $channelIdentifier,
        public string $content,
        public string $contentType = 'text',
        public ?string $externalMessageId = null,
        public array $metadata = [],
        public ?\DateTimeInterface $timestamp = null,
        public array $attachments = [],
    ) {}

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp ?? now();
    }

    public function hasAttachments(): bool
    {
        return ! empty($this->attachments);
    }
}
