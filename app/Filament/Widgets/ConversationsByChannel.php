<?php

namespace App\Filament\Widgets;

use App\Enums\ChannelType;
use App\Models\Conversation;
use Filament\Widgets\ChartWidget;

class ConversationsByChannel extends ChartWidget
{
    protected ?string $heading = 'Conversations by Channel';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $telegram = Conversation::where('last_inbound_channel', ChannelType::TELEGRAM)->count();
        $whatsapp = Conversation::where('last_inbound_channel', ChannelType::WHATSAPP)->count();
        $simulator = Conversation::where('last_inbound_channel', ChannelType::SIMULATOR)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Conversations',
                    'data' => [$telegram, $whatsapp, $simulator],
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',  // Telegram blue
                        'rgb(37, 211, 102)',  // WhatsApp green
                        'rgb(244, 114, 182)',  // Simulator vibrant pink (matches Filament Pink-400)
                    ],
                ],
            ],
            'labels' => ['Telegram', 'WhatsApp', 'Simulator'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    public function getDescription(): ?string
    {
        $total = Conversation::count();
        return "Total: {$total} conversations";
    }
}
