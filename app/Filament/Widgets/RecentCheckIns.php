<?php

namespace App\Filament\Widgets;

use App\Models\Attendee;
use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCheckIns extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function getHeading(): string
    {
        $lastEvent = Event::where('status', 'published')
            ->where('date', '<=', today())
            ->orderBy('date', 'desc')
            ->first();

        return $lastEvent
            ? "Attendees - {$lastEvent->title}"
            : 'Recent Attendees';
    }

    public function table(Table $table): Table
    {
        $lastEvent = Event::where('status', 'published')
            ->where('date', '<=', today())
            ->orderBy('date', 'desc')
            ->first();

        return $table
            ->query(
                Attendee::query()
                    ->with(['member.zone'])
                    ->when($lastEvent, fn ($q) => $q->where('event_id', $lastEvent->id))
                    ->whereNotNull('checked_in_at')
                    ->orderByDesc('checked_in_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('member.phone')
                    ->label('Phone')
                    ->searchable(),
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
                    ->dateTime('g:i A'),
            ])
            ->defaultPaginationPageOption(5)
            ->striped();
    }
}
