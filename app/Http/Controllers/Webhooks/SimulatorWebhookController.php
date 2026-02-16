<?php

namespace App\Http\Controllers\Webhooks;

use App\Channels\ChannelManager;
use App\Enums\ChannelType;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessInboundWebhook;
use App\Models\WebhookLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimulatorWebhookController extends Controller
{
    public function __construct(
        protected ChannelManager $channelManager
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $driver = $this->channelManager->driver(ChannelType::SIMULATOR);

        // Verify webhook authenticity
        if (! $driver->verifyWebhook($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Get payload from JSON or form data
        $payload = $request->isJson() ? $request->json()->all() : $request->all();

        // Log the webhook for debugging and retry capability
        $webhookLog = WebhookLog::create([
            'channel' => ChannelType::SIMULATOR,
            'payload' => $payload,
            'headers' => $request->headers->all(),
            'ip_address' => $request->ip(),
            'status' => 'pending',
        ]);

        // Dispatch to queue for async processing
        ProcessInboundWebhook::dispatch(
            channel: ChannelType::SIMULATOR,
            payload: $payload,
            webhookLogId: $webhookLog->id
        );

        return response()->json([
            'success' => true,
            'webhook_id' => $webhookLog->id,
        ]);
    }
}
