<?php

namespace App\Filament\Resources\Conversations\Pages;

use App\Actions\Messages\SendMessage;
use App\Enums\ContentType;
use App\Enums\ConversationStatus;
use App\Filament\Resources\Conversations\ConversationResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewConversation extends ViewRecord
{
    protected static string $resource = ConversationResource::class;

    public string $messageContent = '';

    public function getView(): string
    {
        return 'filament.resources.conversations.pages.view-conversation';
    }

    public function getMessages()
    {
        // Force fresh query each time
        return $this->record->messages()->orderBy('created_at')->get();
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'messages' => $this->getMessages(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('assign_to_me')
                ->label('Assign to Me')
                ->color('primary')
                ->icon('heroicon-o-user-plus')
                ->visible(fn () => !$this->record->assigned_agent_id || $this->record->assigned_agent_id !== auth()->id())
                ->action(function () {
                    $this->record->update([
                        'assigned_agent_id' => auth()->id(),
                        'status' => ConversationStatus::IN_PROGRESS,
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Conversation assigned')
                        ->body('This conversation has been assigned to you.')
                        ->send();
                }),

            Action::make('resolve')
                ->label('Mark as Resolved')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => $this->record->status !== ConversationStatus::RESOLVED)
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'status' => ConversationStatus::RESOLVED,
                        'resolved_at' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Conversation resolved')
                        ->send();
                }),

            EditAction::make(),
        ];
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->messageContent))) {
            Notification::make()
                ->warning()
                ->title('Message is empty')
                ->body('Please enter a message before sending.')
                ->send();
            return;
        }

        try {
            app(SendMessage::class)->execute(
                conversation: $this->record,
                content: $this->messageContent,
                agentId: auth()->id(),
                contentType: ContentType::TEXT
            );

            // Update conversation status if needed
            if ($this->record->status === ConversationStatus::NEW) {
                $this->record->update([
                    'status' => ConversationStatus::IN_PROGRESS,
                    'assigned_agent_id' => $this->record->assigned_agent_id ?? auth()->id(),
                ]);
            } elseif ($this->record->status === ConversationStatus::WAITING_ON_CUSTOMER) {
                $this->record->update(['status' => ConversationStatus::IN_PROGRESS]);
            }

            // Mark as read
            $this->record->markAsRead();

            // Clear input
            $this->messageContent = '';

            // Refresh the record to show new message
            $this->record->unsetRelation('messages');
            $this->record->refresh();
            $this->record->load('messages');

            Notification::make()
                ->success()
                ->title('Message sent')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Failed to send message')
                ->body($e->getMessage())
                ->send();
        }
    }
}
