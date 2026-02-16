<?php

namespace App\Jobs;

use App\Channels\ChannelManager;
use App\Enums\MessageStatus;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOutboundMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    public function __construct(
        public Message $message
    ) {}

    public function handle(ChannelManager $channelManager): void
    {
        try {
            // Update status to queued
            $this->message->updateStatus(MessageStatus::QUEUED);

            // Get the appropriate channel driver
            $driver = $channelManager->driver($this->message->channel);

            // Validate message before sending
            $validation = $driver->validateOutbound($this->message);

            if (! $validation->valid) {
                throw new \Exception($validation->error);
            }

            // Send the message via channel API
            $response = $driver->send($this->message);

            if ($response->success) {
                // Update message with external ID and mark as sent
                $this->message->update([
                    'channel_message_id' => $response->externalMessageId,
                    'status' => MessageStatus::SENT,
                    'status_updated_at' => now(),
                    'metadata' => array_merge(
                        $this->message->metadata ?? [],
                        $response->metadata
                    ),
                ]);

                Log::info('Message sent successfully', [
                    'message_id' => $this->message->id,
                    'channel' => $this->message->channel->value,
                    'external_id' => $response->externalMessageId,
                ]);

                // Fire event for real-time status update
                // event(new MessageStatusUpdated($this->message));
            } else {
                throw new \Exception($response->error ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            // Mark message as failed
            $this->message->updateStatus(
                MessageStatus::FAILED,
                failureReason: $e->getMessage()
            );

            Log::error('Message send failed', [
                'message_id' => $this->message->id,
                'channel' => $this->message->channel->value,
                'error' => $e->getMessage(),
            ]);

            // Fire event for real-time status update
            // event(new MessageStatusUpdated($this->message));

            // Re-throw to trigger retry
            throw $e;
        }
    }
}
