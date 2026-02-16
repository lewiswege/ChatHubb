<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\RecentConversations::class,
            \App\Filament\Widgets\ConversationsByChannel::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
