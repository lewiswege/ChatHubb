<?php

namespace App\Services;

use App\Enums\ChannelType;
use App\Models\Customer;
use App\Models\CustomerChannelIdentifier;
use Illuminate\Support\Facades\DB;

class CustomerMatchingService
{
    /**
     * Find or create a customer based on channel identifier
     */
    public function findOrCreate(
        ChannelType $channel,
        string $identifier,
        array $metadata = []
    ): Customer {
        return DB::transaction(function () use ($channel, $identifier, $metadata) {
            // Try to find existing customer by channel identifier
            $channelIdentifier = CustomerChannelIdentifier::where('channel', $channel)
                ->where('identifier', $identifier)
                ->first();

            if ($channelIdentifier) {
                return $channelIdentifier->customer;
            }

            // Create new customer
            $customer = Customer::create([
                'name' => $this->extractNameFromMetadata($metadata, $identifier),
                'phone_primary' => $this->extractPhoneIfAvailable($channel, $identifier),
                'metadata' => $metadata,
            ]);

            // Create channel identifier
            CustomerChannelIdentifier::create([
                'customer_id' => $customer->id,
                'channel' => $channel,
                'identifier' => $identifier,
                'metadata' => $metadata,
            ]);

            return $customer;
        });
    }

    /**
     * Extract name from metadata or use identifier as fallback
     */
    protected function extractNameFromMetadata(array $metadata, string $identifier): string
    {
        // Try name field first
        if (! empty($metadata['name'])) {
            return $metadata['name'];
        }

        // Try first_name + last_name
        if (! empty($metadata['first_name']) || ! empty($metadata['last_name'])) {
            return trim(($metadata['first_name'] ?? '').' '.($metadata['last_name'] ?? ''));
        }

        // Try username
        if (! empty($metadata['username'])) {
            return $metadata['username'];
        }

        // Fallback to identifier or default
        return $identifier ?: 'Unknown Customer';
    }

    /**
     * Extract phone number if this is a phone-based channel
     */
    protected function extractPhoneIfAvailable(ChannelType $channel, string $identifier): ?string
    {
        // For WhatsApp, GOIP (SMS), and WAHA, identifier is phone number
        if (in_array($channel, [ChannelType::WHATSAPP, ChannelType::GOIP, ChannelType::WAHA])) {
            return $identifier;
        }

        return null;
    }

    /**
     * Link an existing customer to a new channel identifier
     */
    public function linkChannelToCustomer(
        Customer $customer,
        ChannelType $channel,
        string $identifier,
        array $metadata = []
    ): CustomerChannelIdentifier {
        return CustomerChannelIdentifier::firstOrCreate(
            [
                'channel' => $channel,
                'identifier' => $identifier,
            ],
            [
                'customer_id' => $customer->id,
                'metadata' => $metadata,
            ]
        );
    }
}
