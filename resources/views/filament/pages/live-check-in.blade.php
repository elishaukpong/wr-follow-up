<x-filament-panels::page>
    @if($this->currentEvent)
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->currentEvent->title }}</h2>
                        <p class="text-gray-500 dark:text-gray-400">{{ $this->currentEvent->date->format('l, F j, Y') }} at {{ $this->currentEvent->time->format('g:i A') }}</p>
                    </div>
                    <a href="{{ route('admin.events.qr', $this->currentEvent) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <x-heroicon-o-qr-code class="w-5 h-5" />
                        QR Code
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                        <div class="text-3xl font-bold" wire:poll.5s>{{ $this->stats['total'] }}</div>
                        <div class="text-purple-100 text-sm">Total</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <div class="text-3xl font-bold" wire:poll.5s>{{ $this->stats['male'] }}</div>
                        <div class="text-blue-100 text-sm">Male</div>
                    </div>
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg p-4 text-white">
                        <div class="text-3xl font-bold" wire:poll.5s>{{ $this->stats['female'] }}</div>
                        <div class="text-pink-100 text-sm">Female</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg p-4 text-white">
                        <div class="text-3xl font-bold" wire:poll.5s>{{ $this->stats['first_timers'] }}</div>
                        <div class="text-amber-100 text-sm">First Timers</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
                        <div class="text-3xl font-bold" wire:poll.5s>{{ $this->stats['returning'] }}</div>
                        <div class="text-green-100 text-sm">Returning</div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Recent Check-ins</h3>
        {{ $this->table }}
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center">
            <x-heroicon-o-calendar-days class="w-16 h-16 mx-auto text-gray-400 mb-4" />
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Active Event</h2>
            <p class="text-gray-500 dark:text-gray-400">Create an event to start tracking check-ins.</p>
        </div>
    @endif
</x-filament-panels::page>
