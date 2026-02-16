<?php

namespace App\Channels;

use App\Channels\Contracts\ChannelDriverInterface;
use App\Channels\Drivers\SimulatorDriver;
use App\Enums\ChannelType;
use InvalidArgumentException;

class ChannelManager
{
    protected array $drivers = [];

    public function __construct()
    {
        $this->registerDefaultDrivers();
    }

    protected function registerDefaultDrivers(): void
    {
        $this->register(ChannelType::SIMULATOR, SimulatorDriver::class);
        // Add more as we build them:
        // $this->register(ChannelType::TELEGRAM, TelegramDriver::class);
        // $this->register(ChannelType::WHATSAPP, WhatsAppDriver::class);
    }

    public function register(ChannelType $channel, string $driverClass): void
    {
        $this->drivers[$channel->value] = $driverClass;
    }

    public function driver(ChannelType $channel): ChannelDriverInterface
    {
        if (! isset($this->drivers[$channel->value])) {
            throw new InvalidArgumentException("Channel driver not registered: {$channel->value}");
        }

        $driverClass = $this->drivers[$channel->value];

        return app($driverClass);
    }

    public function getAllDrivers(): array
    {
        return collect($this->drivers)
            ->map(fn ($class) => app($class))
            ->all();
    }

    public function getRegisteredChannels(): array
    {
        return array_keys($this->drivers);
    }
}
