<?php

namespace App\Channels\Contracts;

use App\Channels\ValueObjects\ChannelCapabilities;
use App\Channels\ValueObjects\ChannelResponse;
use App\Channels\ValueObjects\InboundMessage;
use App\Channels\ValueObjects\ValidationResult;
use App\Models\Message;
use Illuminate\Http\Request;

interface ChannelDriverInterface
{
    /**
     * Send a message through this channel
     */
    public function send(Message $message): ChannelResponse;

    /**
     * Parse incoming webhook data into standardized format
     */
    public function parseInboundWebhook(Request $request): InboundMessage;

    /**
     * Get the channel identifier (enum value)
     */
    public function getChannelIdentifier(): string;

    /**
     * Get capabilities of this channel
     */
    public function getCapabilities(): ChannelCapabilities;

    /**
     * Check if channel supports a specific feature
     */
    public function supportsFeature(string $feature): bool;

    /**
     * Validate a message before sending
     */
    public function validateOutbound(Message $message): ValidationResult;

    /**
     * Format message for channel API
     */
    public function formatMessage(Message $message): array;

    /**
     * Verify webhook signature/authenticity
     */
    public function verifyWebhook(Request $request): bool;
}
