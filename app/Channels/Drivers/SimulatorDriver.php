<?php

namespace App\Channels\Drivers;

use App\Channels\Contracts\ChannelDriverInterface;
use App\Channels\ValueObjects\ChannelCapabilities;
use App\Channels\ValueObjects\ChannelResponse;
use App\Channels\ValueObjects\InboundMessage;
use App\Channels\ValueObjects\ValidationResult;
use App\Enums\ChannelType;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SimulatorDriver implements ChannelDriverInterface
{
    public function send(Message $message): ChannelResponse
    {
        // Simulate API delay
        usleep(500000); // 500ms

        // Simulate 95% success rate
        $success = rand(1, 100) <= 95;

        if ($success) {
            return ChannelResponse::success(
                externalMessageId: 'sim_'.Str::ulid(),
                metadata: ['simulated' => true, 'sent_at' => now()->toISOString()]
            );
        }

        return ChannelResponse::failed(
            error: 'Simulated network error',
            metadata: ['simulated' => true]
        );
    }

    public function parseInboundWebhook(Request $request): InboundMessage
    {
        // Try JSON first, fallback to all() for queued requests
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        return new InboundMessage(
            channelIdentifier: $data['from'],
            content: $data['message'],
            contentType: $data['type'] ?? 'text',
            externalMessageId: $data['message_id'] ?? Str::ulid(),
            metadata: $data['metadata'] ?? [],
            timestamp: isset($data['timestamp']) ? new \DateTimeImmutable($data['timestamp']) : null
        );
    }

    public function getChannelIdentifier(): string
    {
        return ChannelType::SIMULATOR->value;
    }

    public function getCapabilities(): ChannelCapabilities
    {
        return new ChannelCapabilities(
            supportsMedia: true,
            supportsBulkSend: true,
            supportsTemplates: false,
            hasReplyWindow: false,
            supportsReadReceipts: true,
            supportsTypingIndicators: true,
            supportsDeliveryStatus: true,
            allowedContentTypes: ['text', 'image', 'document'],
            rateLimitPerMinute: 60
        );
    }

    public function supportsFeature(string $feature): bool
    {
        return match ($feature) {
            'media', 'bulk', 'read_receipts', 'typing', 'delivery_status' => true,
            default => false,
        };
    }

    public function validateOutbound(Message $message): ValidationResult
    {
        if (empty($message->content) && $message->attachments->isEmpty()) {
            return ValidationResult::failed('Message must have content or attachments');
        }

        if (strlen($message->content) > 4096) {
            return ValidationResult::failed('Message too long (max 4096 characters)');
        }

        return ValidationResult::passed();
    }

    public function formatMessage(Message $message): array
    {
        return [
            'to' => $message->conversation->customer->phone_primary,
            'message' => $message->content,
            'type' => $message->content_type->value,
            'metadata' => $message->metadata ?? [],
        ];
    }

    public function verifyWebhook(Request $request): bool
    {
        // Simulator doesn't need verification for testing
        return true;
    }
}
