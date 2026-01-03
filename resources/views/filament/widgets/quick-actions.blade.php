<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-wrap gap-3">
            @foreach($this->getActions() as $action)
                <a href="{{ $action['url'] }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition
                          {{ match($action['color']) {
                              'primary' => 'bg-primary-500 hover:bg-primary-600 text-white',
                              'success' => 'bg-success-500 hover:bg-success-600 text-white',
                              'info' => 'bg-info-500 hover:bg-info-600 text-white',
                              'warning' => 'bg-warning-500 hover:bg-warning-600 text-white',
                              'danger' => 'bg-danger-500 hover:bg-danger-600 text-white',
                              default => 'bg-gray-500 hover:bg-gray-600 text-white',
                          } }}">
                    <x-dynamic-component :component="$action['icon']" class="w-5 h-5" />
                    {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
