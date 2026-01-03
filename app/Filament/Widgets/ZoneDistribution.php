<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Zone;
use Filament\Widgets\ChartWidget;

class ZoneDistribution extends ChartWidget
{
    protected static ?string $heading = 'Members by Zone';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected static ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $zones = Zone::withCount('members')->get();

        $customLocationCount = Member::whereNull('zone_id')
            ->whereNotNull('custom_location')
            ->count();

        $labels = $zones->pluck('name')->toArray();
        $data = $zones->pluck('members_count')->toArray();

        if ($customLocationCount > 0) {
            $labels[] = 'Other Locations';
            $data[] = $customLocationCount;
        }

        $colors = [
            'rgb(251, 191, 36)',
            'rgb(139, 92, 246)',
            'rgb(34, 197, 94)',
            'rgb(59, 130, 246)',
            'rgb(236, 72, 153)',
            'rgb(249, 115, 22)',
            'rgb(20, 184, 166)',
            'rgb(156, 163, 175)',
        ];

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
