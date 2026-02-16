<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
        @if(true) {{-- Desktop: 2 columns --}}
            <style>
                @media (min-width: 1024px) {
                    .chat-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
                }
                .message-bubble {
                    padding: 0.75rem 1rem;
                    border-radius: 1rem;
                    max-width: 75%;
                    margin-bottom: 0.5rem;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                }
                .message-inbound {
                    background: #f3f4f6;
                    color: #111827;
                    border-bottom-left-radius: 0.25rem;
                    margin-right: auto;
                }
                .message-outbound {
                    background: #3b82f6;
                    color: white;
                    border-bottom-right-radius: 0.25rem;
                    margin-left: auto;
                }
                .dark .message-inbound {
                    background: #374151;
                    color: #f9fafb;
                }
                .messages-container {
                    max-height: 600px;
                    overflow-y: auto;
                    padding: 1rem;
                }
            </style>
        @endif

        <div class="chat-grid">
            {{-- Main Chat Area --}}
            <div>
                <x-filament::section>
                    <x-slot name="heading">
                        {{ $record->customer->name }}
                    </x-slot>

                    <x-slot name="description">
                        {{ $record->last_inbound_channel?->label() }} • Last message {{ $record->last_message_at?->diffForHumans() }}
                    </x-slot>

                    {{-- Messages --}}
                    <div class="messages-container">
                        @forelse ($messages as $message)
                            @if($message->direction->value === 'inbound')
                                {{-- Customer Message (Left) --}}
                                <div wire:key="message-{{ $message->id }}" style="display: flex; margin-bottom: 1rem;">
                                    <div style="max-width: 75%;">
                                        <div style="margin-bottom: 0.25rem; display: flex; gap: 0.5rem; align-items: center;">
                                            <span style="font-size: 0.75rem; font-weight: 600; opacity: 0.7;">{{ $record->customer->name }}</span>
                                            <x-filament::badge size="xs" color="{{ $message->channel->color() }}">
                                                {{ $message->channel->label() }}
                                            </x-filament::badge>
                                        </div>
                                        <div class="message-bubble message-inbound">
                                            {{ $message->content }}
                                        </div>
                                        <div style="font-size: 0.7rem; opacity: 0.6; margin-top: 0.25rem;">
                                            {{ $message->created_at->format('M d, g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Agent Message (Right) --}}
                                <div wire:key="message-{{ $message->id }}" style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                                    <div style="max-width: 75%;">
                                        <div style="margin-bottom: 0.25rem; display: flex; gap: 0.5rem; align-items: center; justify-content: flex-end;">
                                            <x-filament::badge size="xs" color="{{ $message->status->color() }}">
                                                {{ $message->status->label() }}
                                            </x-filament::badge>
                                            <span style="font-size: 0.75rem; font-weight: 600; opacity: 0.7;">{{ $message->sentByAgent?->name ?? 'Agent' }}</span>
                                        </div>
                                        <div class="message-bubble message-outbound">
                                            {{ $message->content }}
                                        </div>
                                        <div style="font-size: 0.7rem; opacity: 0.6; margin-top: 0.25rem; text-align: right;">
                                            {{ $message->created_at->format('M d, g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div style="text-align: center; padding: 3rem; opacity: 0.5;">
                                <p>No messages yet</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Message Input --}}
                    <div style="border-top: 1px solid rgba(0,0,0,0.1); padding-top: 1rem; margin-top: 1rem;">
                        <form wire:submit="sendMessage">
                            <textarea
                                wire:model="messageContent"
                                rows="3"
                                placeholder="Type your message..."
                                style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.875rem;"
                            ></textarea>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 0.875rem;">
                                    @if ($record->assigned_agent_id)
                                        <span style="opacity: 0.7;">Assigned to:</span> <strong>{{ $record->assignedAgent->name }}</strong>
                                    @else
                                        <x-filament::badge color="warning">Not assigned</x-filament::badge>
                                    @endif
                                </div>

                                <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                                    Send Message
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                </x-filament::section>
            </div>

            {{-- Sidebar --}}
            <div>
                {{-- Customer Details --}}
                <x-filament::section>
                    <x-slot name="heading">
                        Customer Details
                    </x-slot>

                    <dl style="display: grid; gap: 1rem;">
                        <div>
                            <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Name</dt>
                            <dd style="font-weight: 500;">{{ $record->customer->name }}</dd>
                        </div>

                        @if ($record->customer->phone_primary)
                            <div>
                                <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Phone</dt>
                                <dd style="font-weight: 500;">{{ $record->customer->phone_primary }}</dd>
                            </div>
                        @endif

                        @if ($record->customer->email)
                            <div>
                                <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Email</dt>
                                <dd style="font-weight: 500;">{{ $record->customer->email }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Customer Since</dt>
                            <dd style="font-weight: 500;">{{ $record->customer->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </x-filament::section>

                {{-- Conversation Info --}}
                <x-filament::section style="margin-top: 1.5rem;">
                    <x-slot name="heading">
                        Conversation Info
                    </x-slot>

                    <dl style="display: grid; gap: 1rem;">
                        <div>
                            <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.5rem;">Status</dt>
                            <dd>
                                <x-filament::badge color="{{
                                    match($record->status->value) {
                                        'new' => 'warning',
                                        'in_progress' => 'info',
                                        'waiting_on_customer' => 'gray',
                                        'resolved' => 'success',
                                        default => 'gray'
                                    }
                                }}">
                                    {{ $record->status->label() }}
                                </x-filament::badge>
                            </dd>
                        </div>

                        <div>
                            <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.5rem;">Channel</dt>
                            <dd>
                                <x-filament::badge color="{{ $record->last_inbound_channel?->color() }}">
                                    {{ $record->last_inbound_channel?->label() }}
                                </x-filament::badge>
                            </dd>
                        </div>

                        <div>
                            <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Unread Messages</dt>
                            <dd>
                                @if($record->unread_count > 0)
                                    <x-filament::badge color="danger">{{ $record->unread_count }}</x-filament::badge>
                                @else
                                    <span style="opacity: 0.5;">0</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Started</dt>
                            <dd style="font-size: 0.875rem;">{{ $record->created_at->format('M d, Y g:i A') }}</dd>
                        </div>

                        @if ($record->resolved_at)
                            <div>
                                <dt style="font-size: 0.875rem; opacity: 0.7; margin-bottom: 0.25rem;">Resolved</dt>
                                <dd style="font-size: 0.875rem;">{{ $record->resolved_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
