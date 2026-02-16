<?php

namespace App\Filament\Resources\WebhookLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WebhookLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                BadgeColumn::make('channel')
                    ->label('Channel')
                    ->icon(fn ($record) => $record->channel?->icon())
                    ->color(fn ($record) => $record->channel?->color())
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'processed',
                        'danger' => 'failed',
                    ])
                    ->sortable(),

                TextColumn::make('message.id')
                    ->label('Message')
                    ->limit(20)
                    ->placeholder('Not created')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(50)
                    ->placeholder('No errors')
                    ->color('danger')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at?->diffForHumans()),

                TextColumn::make('processed_at')
                    ->label('Processed At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not processed')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'failed' => 'Failed',
                    ]),

                SelectFilter::make('channel')
                    ->options([
                        'telegram' => 'Telegram',
                        'whatsapp' => 'WhatsApp',
                        'simulator' => 'Simulator',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
