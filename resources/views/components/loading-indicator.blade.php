{{-- Custom Loading Progress Bar --}}
<div
    x-data="{
        loading: false,
        timeout: null,
        showTimeout: null
    }"
    x-init="
        // Listen for Livewire loading events (only user-initiated)
        Livewire.hook('request', ({ uri, options, respond }) => {
            clearTimeout(timeout);
            clearTimeout(showTimeout);

            // Only show for non-polling requests
            if (!options?.url?.includes('poll')) {
                // Delay showing by 150ms to avoid flicker on fast requests
                showTimeout = setTimeout(() => {
                    loading = true;
                }, 150);
            }

            respond(() => {
                clearTimeout(showTimeout);
                timeout = setTimeout(() => {
                    loading = false;
                }, 200);
            });
        });
    "
>
    <div
        class="custom-progress-bar"
        :class="{ 'active': loading }"
        style="display: none;"
        x-show="loading"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-x-0"
        x-transition:enter-end="opacity-100 transform scale-x-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-x-100"
        x-transition:leave-end="opacity-0 transform scale-x-0"
    ></div>
</div>

<style>
    @import url('/css/filament/admin/custom.css');
</style>
