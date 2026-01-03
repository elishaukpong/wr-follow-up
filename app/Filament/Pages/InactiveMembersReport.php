<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Member;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InactiveMembersReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user-minus';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.inactive-members-report';

    protected static ?string $title = 'Inactive Members';

    public function table(Table $table): Table
    {
        // Get the last 2 events
        $lastTwoEventIds = Event::where('status', 'published')
            ->where('date', '<=', today())
            ->orderBy('date', 'desc')
            ->limit(2)
            ->pluck('id');

        return $table
            ->query(
                Member::query()
                    ->has('attendances', '>=', 1) // Has attended at least once
                    ->whereDoesntHave('attendances', function (Builder $query) use ($lastTwoEventIds) {
                        $query->whereIn('event_id', $lastTwoEventIds);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Zone')
                    ->getStateUsing(fn (Member $record) => $record->location ?? '-'),
                Tables\Columns\TextColumn::make('visit_count')
                    ->label('Total Visits')
                    ->getStateUsing(fn (Member $record) => $record->visit_count)
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('last_attended_at')
                    ->label('Last Attended')
                    ->getStateUsing(fn (Member $record) => $record->last_attended_at)
                    ->date('M j, Y'),
                Tables\Columns\TextColumn::make('last_event')
                    ->label('Last Event')
                    ->getStateUsing(fn (Member $record) => $record->attendances()->latest('checked_in_at')->first()?->event?->title ?? '-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('zone')
                    ->relationship('zone', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Member $record) => route('filament.admin.resources.members.view', $record))
                    ->icon('heroicon-o-eye'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => $this->export()),
            ])
            ->defaultSort('name', 'asc')
            ->emptyStateHeading('No inactive members')
            ->emptyStateDescription('Everyone has been attending recently!');
    }

    public function export(): StreamedResponse
    {
        $lastTwoEventIds = Event::where('status', 'published')
            ->where('date', '<=', today())
            ->orderBy('date', 'desc')
            ->limit(2)
            ->pluck('id');

        return response()->streamDownload(function () use ($lastTwoEventIds) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Name', 'Phone', 'Zone', 'Total Visits', 'Last Attended', 'Last Event']);

            Member::has('attendances', '>=', 1)
                ->whereDoesntHave('attendances', function (Builder $query) use ($lastTwoEventIds) {
                    $query->whereIn('event_id', $lastTwoEventIds);
                })
                ->with(['zone', 'attendances.event'])
                ->orderBy('name')
                ->chunk(100, function ($members) use ($handle) {
                    foreach ($members as $member) {
                        $lastAttendance = $member->attendances->sortByDesc('checked_in_at')->first();
                        fputcsv($handle, [
                            $member->name,
                            $member->phone,
                            $member->location ?? '',
                            $member->visit_count,
                            $member->last_attended_at?->format('Y-m-d') ?? '',
                            $lastAttendance?->event?->title ?? '',
                        ]);
                    }
                });

            fclose($handle);
        }, 'inactive-members-' . now()->format('Y-m-d') . '.csv');
    }
}
