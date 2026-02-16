<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Message --}}
        <x-filament::section>
            <x-slot name="heading">
                Welcome back, {{ auth()->user()->name }}!
            </x-slot>

            <x-slot name="description">
                Here's an overview of your ChatHub activity today.
            </x-slot>

            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>Manage customer conversations from multiple channels in one place.</p>
            </div>
        </x-filament::section>

        {{-- Widgets --}}
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
            :columns="$this->getColumns()"
        />
    </div>
</x-filament-panels::page>
