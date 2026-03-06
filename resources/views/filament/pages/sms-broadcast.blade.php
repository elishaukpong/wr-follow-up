<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Compose Form --}}
        <div class="lg:col-span-1">
            <form wire:submit="send">
                {{ $this->form }}

                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Recipients:</span>
                        <span class="font-semibold">{{ number_format($this->getRecipientCount()) }}</span>
                    </div>
                    @php $credits = $this->getCredits(); @endphp
                    @if($credits !== null)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">SMS Credits:</span>
                            <span class="font-semibold">{{ number_format($credits) }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <x-filament::button type="submit" class="w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="send">Send Broadcast</span>
                        <span wire:loading wire:target="send">Sending...</span>
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- Broadcast History --}}
        <div class="lg:col-span-2">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
