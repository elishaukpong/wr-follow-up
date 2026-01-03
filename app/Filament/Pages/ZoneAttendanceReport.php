<?php

namespace App\Filament\Pages;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ZoneAttendanceReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.zone-attendance-report';

    protected static ?string $title = 'Zone Attendance';

    public ?string $event_filter = 'all';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_filter')
                    ->label('Event')
                    ->options(
                        ['all' => 'All Events (Total Members)'] +
                        Event::orderBy('date', 'desc')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => $e->title . ' (' . $e->date->format('M Y') . ')'])
                            ->toArray()
                    )
                    ->default('all')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),
            ])
            ->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Zone::query()->where('is_active', true)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Zone')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_members')
                    ->label('Total Members')
                    ->getStateUsing(fn (Zone $record) => $record->members()->count())
                    ->sortable(query: fn (Builder $query, string $direction) =>
                        $query->withCount('members')->orderBy('members_count', $direction)
                    ),
                Tables\Columns\TextColumn::make('attendance')
                    ->label($this->event_filter === 'all' ? 'Total Attendances' : 'Event Attendance')
                    ->getStateUsing(function (Zone $record) {
                        if ($this->event_filter === 'all') {
                            return Attendee::whereHas('member', fn ($q) => $q->where('zone_id', $record->id))->count();
                        }
                        return Attendee::where('event_id', $this->event_filter)
                            ->whereHas('member', fn ($q) => $q->where('zone_id', $record->id))
                            ->count();
                    })
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('first_timers')
                    ->label('First Timers')
                    ->getStateUsing(function (Zone $record) {
                        $query = $record->members()->has('attendances', '=', 1);
                        if ($this->event_filter !== 'all') {
                            $query->whereHas('attendances', fn ($q) => $q->where('event_id', $this->event_filter));
                        }
                        return $query->count();
                    })
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('attendance_rate')
                    ->label('Attendance Rate')
                    ->getStateUsing(function (Zone $record) {
                        $totalMembers = $record->members()->count();
                        if ($totalMembers === 0) return '0%';

                        if ($this->event_filter === 'all') {
                            // Average attendance rate across all events
                            $eventCount = Event::where('status', 'published')->count();
                            if ($eventCount === 0) return '0%';
                            $totalAttendances = Attendee::whereHas('member', fn ($q) => $q->where('zone_id', $record->id))->count();
                            $rate = ($totalAttendances / ($totalMembers * $eventCount)) * 100;
                        } else {
                            $attended = Attendee::where('event_id', $this->event_filter)
                                ->whereHas('member', fn ($q) => $q->where('zone_id', $record->id))
                                ->count();
                            $rate = ($attended / $totalMembers) * 100;
                        }
                        return round($rate) . '%';
                    }),
            ])
            ->defaultSort('name')
            ->striped();
    }
}
