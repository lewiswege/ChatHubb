<?php

namespace App\Channels\ValueObjects;

readonly class ChannelCapabilities
{
    public function __construct(
        public bool $supportsMedia = false,
        public bool $supportsBulkSend = false,
        public bool $supportsTemplates = false,
        public bool $hasReplyWindow = false,
        public ?int $replyWindowHours = null,
        public bool $supportsReadReceipts = false,
        public bool $supportsTypingIndicators = false,
        public bool $supportsDeliveryStatus = false,
        public bool $canInitiateConversation = true,
        public array $allowedContentTypes = ['text'],
        public ?int $rateLimitPerMinute = null,
    ) {}

    public function allows(string $contentType): bool
    {
        return in_array($contentType, $this->allowedContentTypes);
    }

    public function isWithinReplyWindow(\DateTimeInterface $lastInboundMessage): bool
    {
        if (! $this->hasReplyWindow) {
            return true;
        }

        $cutoff = now()->subHours($this->replyWindowHours);

        return $lastInboundMessage > $cutoff;
    }
}
