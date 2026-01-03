<?php

namespace App\Filament\Pages;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Member;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;

class LiveCheckIn extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationGroup = 'Events';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.live-check-in';

    protected static ?string $title = 'Live Check-in';

    #[Computed]
    public function currentEvent(): ?Event
    {
        // Get today's event or the most recent upcoming event
        return Event::where('status', 'published')
            ->where('date', '>=', today())
            ->orderBy('date')
            ->first()
            ?? Event::where('status', 'published')
                ->where('date', '<=', today())
                ->orderBy('date', 'desc')
                ->first();
    }

    #[Computed]
    public function stats(): array
    {
        $event = $this->currentEvent;

        if (!$event) {
            return [
                'total' => 0,
                'male' => 0,
                'female' => 0,
                'first_timers' => 0,
                'returning' => 0,
            ];
        }

        $attendees = Attendee::where('event_id', $event->id)->with('member')->get();

        return [
            'total' => $attendees->count(),
            'male' => $attendees->filter(fn ($a) => $a->member?->gender?->value === 'male')->count(),
            'female' => $attendees->filter(fn ($a) => $a->member?->gender?->value === 'female')->count(),
            'first_timers' => $attendees->filter(fn ($a) => $a->member?->visit_count === 1)->count(),
            'returning' => $attendees->filter(fn ($a) => $a->member?->visit_count > 1)->count(),
        ];
    }

    public function table(Table $table): Table
    {
        $event = $this->currentEvent;

        return $table
            ->query(
                Attendee::query()
                    ->when($event, fn ($q) => $q->where('event_id', $event->id))
                    ->when(!$event, fn ($q) => $q->whereRaw('1 = 0'))
                    ->with('member.zone')
                    ->latest('checked_in_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('member.gender')
                    ->label('Gender')
                    ->badge(),
                Tables\Columns\TextColumn::make('member.location')
                    ->label('Zone')
                    ->getStateUsing(fn (Attendee $record) => $record->member?->location ?? '-'),
                Tables\Columns\TextColumn::make('member.visit_status')
                    ->label('Status')
                    ->getStateUsing(fn (Attendee $record) => $record->member?->visit_status ?? '-')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'First Timer' => 'warning',
                        'Second Timer' => 'info',
                        'Third Timer' => 'primary',
                        'Regular' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->dateTime('g:i:s A')
                    ->sortable(),
            ])
            ->poll('5s')
            ->defaultSort('checked_in_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
