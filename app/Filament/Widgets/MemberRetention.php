<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberRetention extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        // Get last 3 events
        $recentEvents = Event::where('status', 'published')
            ->where('date', '<=', today())
            ->orderBy('date', 'desc')
            ->limit(3)
            ->get();

        // Members who attended all recent events
        $consistentMembers = 0;
        if ($recentEvents->count() >= 2) {
            $consistentMembers = Member::whereHas('attendances', function ($query) use ($recentEvents) {
                $query->whereIn('event_id', $recentEvents->pluck('id'));
            }, '>=', $recentEvents->count())->count();
        }

        // Members who haven't attended in last 2 events
        $lastTwoEvents = $recentEvents->take(2)->pluck('id');
        $inactiveMembers = Member::has('attendances', '>=', 1)
            ->whereDoesntHave('attendances', function ($query) use ($lastTwoEvents) {
                $query->whereIn('event_id', $lastTwoEvents);
            })
            ->count();

        // Return rate: % of first timers who came back for a second event
        $totalFirstTimersEver = Member::has('attendances', '>=', 1)->count();
        $returnedMembers = Member::has('attendances', '>=', 2)->count();
        $returnRate = $totalFirstTimersEver > 0
            ? round(($returnedMembers / $totalFirstTimersEver) * 100)
            : 0;

        // Birthdays this month
        $birthdaysThisMonth = Member::whereMonth('birthday', now()->month)->count();

        return [
            Stat::make('Return Rate', $returnRate . '%')
                ->description('First timers who came back')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($returnRate >= 50 ? 'success' : ($returnRate >= 30 ? 'warning' : 'danger')),

            Stat::make('Consistent Members', $consistentMembers)
                ->description('Attended last ' . $recentEvents->count() . ' events')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Inactive Members', $inactiveMembers)
                ->description('Missed last 2 events')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($inactiveMembers > 0 ? 'warning' : 'success'),

            Stat::make('Birthdays This Month', $birthdaysThisMonth)
                ->description(now()->format('F'))
                ->descriptionIcon('heroicon-m-cake')
                ->color('info'),
        ];
    }
}
