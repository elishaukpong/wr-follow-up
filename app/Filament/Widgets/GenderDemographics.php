<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;

class GenderDemographics extends ChartWidget
{
    protected static ?string $heading = 'Gender Distribution';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    protected static ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $male = Member::where('gender', 'male')->count();
        $female = Member::where('gender', 'female')->count();
        $notSet = Member::whereNull('gender')->count();

        $data = [$male, $female];
        $labels = ['Male', 'Female'];
        $colors = ['rgb(59, 130, 246)', 'rgb(236, 72, 153)'];

        if ($notSet > 0) {
            $data[] = $notSet;
            $labels[] = 'Not Set';
            $colors[] = 'rgb(156, 163, 175)';
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
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
