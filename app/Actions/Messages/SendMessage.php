<?php

namespace App\Actions\Messages;

use App\Channels\ChannelManager;
use App\Enums\ContentType;
use App\Enums\MessageDirection;
use App\Enums\MessageStatus;
use App\Jobs\SendOutboundMessage;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class SendMessage
{
    public function __construct(
        protected ChannelManager $channelManager
    ) {}

    /**
     * Send a message to a customer through a conversation
     */
    public function execute(
        Conversation $conversation,
        string $content,
        int $agentId,
        ContentType $contentType = ContentType::TEXT,
        ?array $attachments = null,
        ?array $metadata = null
    ): Message {
        return DB::transaction(function () use ($conversation, $content, $agentId, $contentType, $attachments, $metadata) {
            // Determine which channel to use (use last inbound channel by default)
            $channel = $conversation->last_inbound_channel;

            if (! $channel) {
                throw new \Exception('No channel available for this conversation');
            }

            // Validate channel capabilities
            $driver = $this->channelManager->driver($channel);
            $capabilities = $driver->getCapabilities();

            // Check content type is supported
            if (! $capabilities->allows($contentType->value)) {
                throw new \Exception("Channel {$channel->label()} does not support {$contentType->label()}");
            }

            // Check reply window if applicable
            if ($capabilities->hasReplyWindow) {
                $lastInbound = $conversation->messages()
                    ->where('direction', MessageDirection::INBOUND)
                    ->latest()
                    ->first();

                if ($lastInbound && ! $capabilities->isWithinReplyWindow($lastInbound->created_at)) {
                    throw new \Exception("Reply window expired for {$channel->label()}. Use a template message instead.");
                }
            }

            // Create the message in pending status
            $message = $conversation->messages()->create([
                'direction' => MessageDirection::OUTBOUND,
                'channel' => $channel,
                'content_type' => $contentType,
                'content' => $content,
                'metadata' => $metadata ?? [],
                'status' => MessageStatus::PENDING,
                'status_updated_at' => now(),
                'sent_by_agent_id' => $agentId,
            ]);

            // Handle attachments if provided
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $message->attachments()->create([
                        'type' => $attachment['type'] ?? 'unknown',
                        'file_path' => $attachment['path'] ?? '',
                        'file_name' => $attachment['name'] ?? 'attachment',
                        'file_size' => $attachment['size'] ?? 0,
                        'mime_type' => $attachment['mime_type'] ?? 'application/octet-stream',
                    ]);
                }
            }

            // Update conversation
            $conversation->update([
                'last_message_at' => $message->created_at,
            ]);

            // Dispatch job to send the message asynchronously
            SendOutboundMessage::dispatch($message);

            // Fire event for real-time UI updates
            // event(new MessageSent($message));

            return $message;
        });
    }
}
