<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Conversations\ConversationResource;
use App\Models\Conversation;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentConversations extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Conversations')
            ->query(
                Conversation::query()
                    ->latest('last_message_at')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-o-user'),

                TextColumn::make('last_message_preview')
                    ->label('Last Message')
                    ->getStateUsing(function ($record) {
                        $lastMessage = $record->messages()->latest()->first();
                        if (!$lastMessage) return 'No messages yet';

                        $prefix = $lastMessage->direction->value === 'outbound' ? 'You: ' : '';
                        return $prefix . \Illuminate\Support\Str::limit($lastMessage->content, 50);
                    })
                    ->color('gray'),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'new',
                        'info' => 'in_progress',
                        'gray' => 'waiting_on_customer',
                        'success' => 'resolved',
                    ]),

                BadgeColumn::make('last_inbound_channel')
                    ->label('Channel')
                    ->icon(fn ($record) => $record->last_inbound_channel?->icon())
                    ->color(fn ($record) => $record->last_inbound_channel?->color()),

                BadgeColumn::make('unread_count')
                    ->label('Unread')
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state : null),

                TextColumn::make('last_message_at')
                    ->label('Last Activity')
                    ->since()
                    ->sortable(),
            ])
            ->recordUrl(fn ($record) => ConversationResource::getUrl('view', ['record' => $record]))
            ->paginated(false);
    }
}
