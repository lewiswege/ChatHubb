<?php

namespace App\Jobs;

use App\Channels\ChannelManager;
use App\Enums\ChannelType;
use App\Models\WebhookLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessInboundWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    public function __construct(
        public ChannelType $channel,
        public array $payload,
        public string $webhookLogId
    ) {}

    public function handle(ChannelManager $channelManager): void
    {
        try {
            $driver = $channelManager->driver($this->channel);

            // Create a mock request from payload
            $request = request()->create('/', 'POST', $this->payload);

            // Parse webhook into standardized format
            $inboundMessage = $driver->parseInboundWebhook($request);

            // Process the message (we'll create this action next)
            $message = app(\App\Actions\Messages\ProcessInboundMessage::class)
                ->execute($inboundMessage, $this->channel);

            // Update webhook log as successful
            WebhookLog::find($this->webhookLogId)->update([
                'processed_at' => now(),
                'status' => 'success',
                'message_id' => $message->id,
            ]);
        } catch (\Exception $e) {
            // Update webhook log as failed
            WebhookLog::find($this->webhookLogId)->update([
                'processed_at' => now(),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Webhook processing failed', [
                'channel' => $this->channel->value,
                'webhook_id' => $this->webhookLogId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }
}
