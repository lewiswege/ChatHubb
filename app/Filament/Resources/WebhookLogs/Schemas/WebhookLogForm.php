<?php

namespace App\Filament\Resources\WebhookLogs\Schemas;

use App\Enums\ChannelType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WebhookLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('channel')
                    ->options(ChannelType::class)
                    ->required(),
                TextInput::make('payload')
                    ->required(),
                TextInput::make('headers'),
                TextInput::make('ip_address'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('processed_at'),
                Select::make('message_id')
                    ->relationship('message', 'id'),
                Textarea::make('error_message')
                    ->columnSpanFull(),
            ]);
    }
}
