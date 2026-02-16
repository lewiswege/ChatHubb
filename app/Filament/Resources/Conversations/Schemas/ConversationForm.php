<?php

namespace App\Filament\Resources\Conversations\Schemas;

use Filament\Schemas\Schema;

class ConversationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Form not needed - conversations are viewed with custom chat interface
                // Create functionality handled by ListConversations header action
            ]);
    }
}
