<?php

namespace App\Filament\Widgets;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalMembers = Member::count();

        // Get the most recent event
        $lastEvent = Event::where('status', 'published')
            ->where('date', '<=', today())
            ->orderBy('date', 'desc')
            ->first();

        $lastEventAttendance = $lastEvent
            ? $lastEvent->attendees()->count()
            : 0;

        // Get next upcoming event
        $nextEvent = Event::where('status', 'published')
            ->where('date', '>=', today())
            ->orderBy('date')
            ->first();

        // First timers at last event
        $firstTimersLastEvent = $lastEvent
            ? Attendee::where('event_id', $lastEvent->id)
                ->whereHas('member', fn ($q) => $q->has('attendances', '=', 1))
                ->count()
            : 0;

        // Growth: compare last event to previous event
        $previousEvent = Event::where('status', 'published')
            ->where('date', '<', $lastEvent?->date ?? today())
            ->orderBy('date', 'desc')
            ->first();

        $previousAttendance = $previousEvent?->attendees()->count() ?? 0;
        $attendanceChange = $previousAttendance > 0
            ? round((($lastEventAttendance - $previousAttendance) / $previousAttendance) * 100, 1)
            : 0;

        return [
            Stat::make('Total Members', $totalMembers)
                ->description('All registered members')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Last Event Attendance', $lastEventAttendance)
                ->description($lastEvent ? $lastEvent->title : 'No events yet')
                ->descriptionIcon($attendanceChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($attendanceChange >= 0 ? 'success' : 'danger'),

            Stat::make('First Timers (Last Event)', $firstTimersLastEvent)
                ->description($lastEvent ? $lastEvent->date->format('M j, Y') : '-')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Next Event', $nextEvent ? $nextEvent->date->format('M j') : 'None')
                ->description($nextEvent ? $nextEvent->title : 'No upcoming events')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
        ];
    }
}
