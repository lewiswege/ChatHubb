<?php

namespace App\Actions\Messages;

use App\Channels\ValueObjects\InboundMessage;
use App\Enums\ChannelType;
use App\Enums\ContentType;
use App\Enums\ConversationStatus;
use App\Enums\MessageDirection;
use App\Enums\MessageStatus;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\CustomerMatchingService;
use Illuminate\Support\Facades\DB;

class ProcessInboundMessage
{
    public function __construct(
        protected CustomerMatchingService $customerMatcher
    ) {}

    /**
     * Process an inbound message from any channel
     */
    public function execute(InboundMessage $inbound, ChannelType $channel): Message
    {
        return DB::transaction(function () use ($inbound, $channel) {
            // Step 1: Find or create customer
            $customer = $this->customerMatcher->findOrCreate(
                channel: $channel,
                identifier: $inbound->channelIdentifier,
                metadata: $inbound->metadata
            );

            // Step 2: Find or create conversation (one per customer)
            $conversation = Conversation::firstOrCreate(
                ['customer_id' => $customer->id],
                [
                    'status' => ConversationStatus::NEW,
                    'last_message_at' => $inbound->getTimestamp(),
                    'last_inbound_channel' => $channel,
                    'unread_count' => 0,
                ]
            );

            // Step 3: Create the message
            $message = $conversation->messages()->create([
                'direction' => MessageDirection::INBOUND,
                'channel' => $channel,
                'channel_message_id' => $inbound->externalMessageId,
                'content_type' => ContentType::from($inbound->contentType),
                'content' => $inbound->content,
                'metadata' => $inbound->metadata,
                'status' => MessageStatus::DELIVERED, // Inbound messages are already delivered
                'status_updated_at' => now(),
            ]);

            // Step 4: Handle attachments if any
            if ($inbound->hasAttachments()) {
                foreach ($inbound->attachments as $attachment) {
                    $message->attachments()->create([
                        'type' => $attachment['type'] ?? 'unknown',
                        'file_path' => $attachment['path'] ?? '',
                        'file_name' => $attachment['name'] ?? 'attachment',
                        'file_size' => $attachment['size'] ?? 0,
                        'mime_type' => $attachment['mime_type'] ?? 'application/octet-stream',
                    ]);
                }
            }

            // Step 5: Update conversation metadata
            $conversation->update([
                'last_message_at' => $message->created_at,
                'last_inbound_channel' => $channel,
            ]);

            // Step 6: Increment unread count
            $conversation->incrementUnread();

            // Step 7: Update conversation status if needed
            if ($conversation->status === ConversationStatus::RESOLVED) {
                // Customer replied to a resolved conversation - reopen it
                $conversation->update([
                    'status' => ConversationStatus::IN_PROGRESS,
                    'resolved_at' => null,
                ]);
            }

            // Step 8: Fire event for real-time updates (we'll create this later)
            // event(new MessageReceived($message));

            return $message;
        });
    }
}
