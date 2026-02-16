<?php

namespace App\Filament\Resources\Conversations\Pages;

use App\Enums\ChannelType;
use App\Enums\ConversationStatus;
use App\Filament\Resources\Conversations\ConversationResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListConversations extends ListRecords
{
    protected static string $resource = ConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->form([
                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
                        ->searchable(['name', 'phone_primary', 'email'])
                        ->preload()
                        ->required()
                        ->native(false)  // Use custom select for better create UX
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Customer Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter customer name'),
                            TextInput::make('phone_primary')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(20)
                                ->placeholder('+1234567890'),
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->maxLength(255)
                                ->placeholder('customer@example.com'),
                        ])
                        ->createOptionModalHeading('Create New Customer')
                        ->createOptionAction(fn ($action) => $action->modalWidth('md')),

                    Select::make('last_inbound_channel')
                        ->label('Channel')
                        ->options([
                            ChannelType::TELEGRAM->value => 'Telegram',
                            ChannelType::WHATSAPP->value => 'WhatsApp',
                            ChannelType::SIMULATOR->value => 'Simulator',
                        ])
                        ->required()
                        ->default(ChannelType::SIMULATOR->value),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            ConversationStatus::NEW->value => 'New',
                            ConversationStatus::IN_PROGRESS->value => 'In Progress',
                            ConversationStatus::WAITING_ON_CUSTOMER->value => 'Waiting on Customer',
                            ConversationStatus::RESOLVED->value => 'Resolved',
                        ])
                        ->default(ConversationStatus::NEW->value)
                        ->required(),

                    Select::make('assigned_agent_id')
                        ->label('Assigned Agent')
                        ->relationship('assignedAgent', 'name')
                        ->searchable()
                        ->preload()
                        ->default(fn () => auth()->id()),
                ])
                ->mutateFormDataUsing(function (array $data): array {
                    $data['last_message_at'] = now();
                    return $data;
                })
                ->successRedirectUrl(fn ($record) => ConversationResource::getUrl('view', ['record' => $record])),
        ];
    }
}
