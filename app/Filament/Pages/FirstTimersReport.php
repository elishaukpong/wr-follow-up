<?php

namespace App\Filament\Pages;

use App\Enums\FollowUpStatus;
use App\Models\Event;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FirstTimersReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.first-timers-report';

    protected static ?string $title = 'First Timers';

    public ?string $event_filter = 'all';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_filter')
                    ->label('Show first timers from')
                    ->options(
                        ['all' => 'All Events'] +
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
                Member::query()
                    ->has('attendances', '=', 1) // Exactly 1 attendance ever = First Timer
                    ->when($this->event_filter !== 'all', function (Builder $query) {
                        // Filter to show only first timers whose single visit was at this event
                        $query->whereHas('attendances', function (Builder $q) {
                            $q->where('event_id', $this->event_filter);
                        });
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Zone')
                    ->getStateUsing(fn (Member $record) => $record->location ?? '-'),
                Tables\Columns\TextColumn::make('follow_up_status')
                    ->label('Follow-up')
                    ->badge()
                    ->placeholder('Not contacted'),
                Tables\Columns\TextColumn::make('attendances.event.title')
                    ->label('Attended')
                    ->getStateUsing(fn (Member $record) => $record->attendances->first()?->event?->title ?? '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('follow_up_status')
                    ->options([
                        'not_contacted' => 'Not Contacted',
                        'pending' => 'Pending',
                        'contacted' => 'Contacted',
                        'connected' => 'Connected',
                        'no_response' => 'No Response',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'not_contacted') {
                            return $query->whereNull('follow_up_status');
                        }
                        if ($data['value']) {
                            return $query->where('follow_up_status', $data['value']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Member $record) => route('filament.admin.resources.members.view', $record))
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('updateFollowUp')
                        ->label('Update Follow-up Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('follow_up_status')
                                ->label('Follow-up Status')
                                ->options(FollowUpStatus::class)
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update([
                                    'follow_up_status' => $data['follow_up_status'],
                                    'followed_up_at' => now(),
                                ]);
                            });

                            Notification::make()
                                ->title('Follow-up status updated for ' . $records->count() . ' members')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => $this->export()),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No first timers')
            ->emptyStateDescription('All members have attended more than once - great retention!');
    }

    public function export(): StreamedResponse
    {
        $filename = $this->event_filter === 'all'
            ? 'first-timers-all'
            : 'first-timers-' . str(Event::find($this->event_filter)?->title ?? 'event')->slug();

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Name', 'Phone', 'Gender', 'Zone', 'Follow-up Status', 'Event Attended', 'Joined']);

            Member::has('attendances', '=', 1)
                ->when($this->event_filter !== 'all', function (Builder $query) {
                    $query->whereHas('attendances', function (Builder $q) {
                        $q->where('event_id', $this->event_filter);
                    });
                })
                ->with(['zone', 'attendances.event'])
                ->orderBy('name')
                ->chunk(100, function ($members) use ($handle) {
                    foreach ($members as $member) {
                        fputcsv($handle, [
                            $member->name,
                            $member->phone,
                            $member->gender?->value ?? '',
                            $member->location ?? '',
                            $member->follow_up_status?->value ?? 'not contacted',
                            $member->attendances->first()?->event?->title ?? '',
                            $member->created_at->format('Y-m-d'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename . '-' . now()->format('Y-m-d') . '.csv');
    }
}
