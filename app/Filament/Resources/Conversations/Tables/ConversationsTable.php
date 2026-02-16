<?php

namespace App\Filament\Resources\Conversations\Tables;

use App\Enums\ConversationStatus;
use App\Filament\Resources\Conversations\ConversationResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConversationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->columns([
                Split::make([
                    Stack::make([
                        Split::make([
                            TextColumn::make('customer.name')
                                ->label('Customer')
                                ->searchable()
                                ->sortable()
                                ->weight(FontWeight::Bold)
                                ->size('md')
                                ->grow(false),

                            BadgeColumn::make('unread_count')
                                ->label('')
                                ->color('danger')
                                ->formatStateUsing(fn ($state) => $state > 0 ? $state : null)
                                ->grow(false),
                        ]),

                        TextColumn::make('last_message_preview')
                            ->label('')
                            ->getStateUsing(function ($record) {
                                $lastMessage = $record->messages()->latest()->first();
                                if (!$lastMessage) return 'No messages yet';

                                $prefix = $lastMessage->direction->value === 'outbound' ? 'You: ' : '';
                                return $prefix . \Illuminate\Support\Str::limit($lastMessage->content, 60);
                            })
                            ->color('gray')
                            ->size('sm'),

                        Split::make([
                            BadgeColumn::make('last_inbound_channel')
                                ->label('')
                                ->icon(fn ($record) => $record->last_inbound_channel?->icon())
                                ->color(fn ($record) => $record->last_inbound_channel?->color())
                                ->size('xs'),

                            BadgeColumn::make('status')
                                ->label('')
                                ->colors([
                                    'warning' => ConversationStatus::NEW->value,
                                    'info' => ConversationStatus::IN_PROGRESS->value,
                                    'gray' => ConversationStatus::WAITING_ON_CUSTOMER->value,
                                    'success' => ConversationStatus::RESOLVED->value,
                                ])
                                ->size('xs'),

                            TextColumn::make('assignedAgent.name')
                                ->label('')
                                ->placeholder('Unassigned')
                                ->color('gray')
                                ->size('xs')
                                ->icon('heroicon-o-user')
                                ->grow(false),
                        ])->from('md'),
                    ]),

                    Stack::make([
                        TextColumn::make('last_message_at')
                            ->label('')
                            ->dateTime('M d, g:i A')
                            ->size('xs')
                            ->color('gray')
                            ->alignEnd(),

                        TextColumn::make('time_ago')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->last_message_at?->diffForHumans())
                            ->size('xs')
                            ->color('gray')
                            ->weight(FontWeight::Medium)
                            ->alignEnd(),
                    ])->grow(false)->alignEnd(),
                ])->from('md'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ConversationStatus::NEW->value => 'New',
                        ConversationStatus::IN_PROGRESS->value => 'In Progress',
                        ConversationStatus::WAITING_ON_CUSTOMER->value => 'Waiting on Customer',
                        ConversationStatus::RESOLVED->value => 'Resolved',
                    ]),

                SelectFilter::make('assigned_agent_id')
                    ->label('Assigned Agent')
                    ->relationship('assignedAgent', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('last_inbound_channel')
                    ->label('Channel')
                    ->options([
                        'telegram' => 'Telegram',
                        'whatsapp' => 'WhatsApp',
                        'simulator' => 'Simulator',
                    ]),
            ])
            ->defaultSort('last_message_at', 'desc')
            ->recordUrl(fn ($record) => ConversationResource::getUrl('view', ['record' => $record]))
            ->recordAction(null)
            ->striped()
            ->paginated([10, 25, 50])
            ->poll('30s')
            ->deferLoading()
            ->recordClasses(fn ($record) =>
                $record->unread_count > 0
                    ? 'bg-primary-50 dark:bg-primary-950/20 hover:bg-primary-100 dark:hover:bg-primary-950/30'
                    : 'hover:bg-gray-50 dark:hover:bg-gray-800/50'
            )
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
