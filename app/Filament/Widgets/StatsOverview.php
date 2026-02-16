<?php

namespace App\Filament\Widgets;

use App\Enums\ConversationStatus;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalConversations = Conversation::count();
        $activeConversations = Conversation::whereIn('status', [
            ConversationStatus::NEW,
            ConversationStatus::IN_PROGRESS,
        ])->count();

        $unreadCount = Conversation::sum('unread_count');

        $totalCustomers = Customer::count();
        $newCustomersToday = Customer::whereDate('created_at', today())->count();

        $totalMessages = Message::count();
        $messagesToday = Message::whereDate('created_at', today())->count();

        return [
            Stat::make('Total Conversations', $totalConversations)
                ->description($activeConversations . ' active')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary')
                ->chart([7, 12, 15, 18, 22, 25, $totalConversations]),

            Stat::make('Unread Messages', $unreadCount)
                ->description('Need attention')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadCount > 0 ? 'danger' : 'success'),

            Stat::make('Total Customers', $totalCustomers)
                ->description($newCustomersToday . ' new today')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([15, 18, 22, 25, 28, 30, $totalCustomers]),

            Stat::make('Messages Today', $messagesToday)
                ->description('Out of ' . $totalMessages . ' total')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info'),
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return null; // Disable auto-polling to prevent progress bar flickering
    }
}
