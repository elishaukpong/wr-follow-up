<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;

class CheckInsChart extends ChartWidget
{
    protected static ?string $heading = 'Attendance per Event';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected static ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $events = Event::withCount('attendees')
            ->orderBy('date', 'desc')
            ->limit(12)
            ->get()
            ->reverse();

        $labels = $events->map(fn ($e) => $e->date->format('M Y'))->toArray();
        $data = $events->pluck('attendees_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Attendees',
                    'data' => $data,
                    'backgroundColor' => 'rgba(251, 191, 36, 0.8)',
                    'borderColor' => 'rgb(251, 191, 36)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
