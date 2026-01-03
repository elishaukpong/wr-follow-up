<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\MemberResource;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.quick-actions';

    public function getActions(): array
    {
        return [
            [
                'label' => 'New Event',
                'icon' => 'heroicon-o-calendar-plus',
                'url' => EventResource::getUrl('create'),
                'color' => 'primary',
            ],
            [
                'label' => 'Add Member',
                'icon' => 'heroicon-o-user-plus',
                'url' => MemberResource::getUrl('create'),
                'color' => 'success',
            ],
            [
                'label' => 'Live Check-in',
                'icon' => 'heroicon-o-signal',
                'url' => route('filament.admin.pages.live-check-in'),
                'color' => 'info',
            ],
            [
                'label' => 'First Timers',
                'icon' => 'heroicon-o-star',
                'url' => route('filament.admin.pages.first-timers-report'),
                'color' => 'warning',
            ],
        ];
    }
}
