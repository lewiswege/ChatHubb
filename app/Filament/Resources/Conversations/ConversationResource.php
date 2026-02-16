<?php

namespace App\Filament\Resources\Conversations;

use App\Filament\Resources\Conversations\Pages\CreateConversation;
use App\Filament\Resources\Conversations\Pages\EditConversation;
use App\Filament\Resources\Conversations\Pages\ListConversations;
use App\Filament\Resources\Conversations\Pages\ViewConversation;
use App\Filament\Resources\Conversations\Schemas\ConversationForm;
use App\Filament\Resources\Conversations\Schemas\ConversationInfolist;
use App\Filament\Resources\Conversations\Tables\ConversationsTable;
use App\Models\Conversation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Conversations';

    protected static ?string $modelLabel = 'Conversation';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'customer.name';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['customer.name', 'customer.phone_primary', 'customer.email'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Customer' => $record->customer->name,
            'Status' => $record->status->label(),
            'Channel' => $record->last_inbound_channel?->label(),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return ConversationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConversationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConversationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConversations::route('/'),
            'view' => ViewConversation::route('/{record}'),
            'edit' => EditConversation::route('/{record}/edit'),
        ];
    }
}
