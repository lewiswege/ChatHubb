<?php

namespace App\Filament\Resources\Conversations\Pages;

use App\Filament\Resources\Conversations\ConversationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateConversation extends CreateRecord
{
    protected static string $resource = ConversationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['last_message_at'] = now();

        return $data;
    }
}
