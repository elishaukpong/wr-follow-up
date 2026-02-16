<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MemberResource;
use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingBirthdays extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Birthdays';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('bold')
                    ->url(fn (Member $record) => MemberResource::getUrl('view', ['record' => $record])),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Birthday')
                    ->formatStateUsing(function (Member $record) {
                        $birthday = $record->birthday;
                        $thisYear = $birthday->copy()->year(now()->year);

                        if ($thisYear->isPast() && !$thisYear->isToday()) {
                            $thisYear->addYear();
                        }

                        $daysUntil = now()->startOfDay()->diffInDays($thisYear->startOfDay());

                        if ($daysUntil === 0) {
                            return $birthday->format('M j') . ' — Today!';
                        }

                        return $birthday->format('M j') . ' — ' . $daysUntil . 'd';
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-o-phone')
                    ->iconColor('gray')
                    ->size('sm')
                    ->color('gray'),
            ])
            ->paginated(false)
            ->defaultSort('birthday_sort');
    }

    protected function getTableQuery(): Builder
    {
        $today = now();
        $weekFromNow = now()->addDays(7);

        return Member::query()
            ->whereNotNull('birthday')
            ->where(function (Builder $query) use ($today, $weekFromNow) {
                if ($today->month <= $weekFromNow->month) {
                    $query->where(function ($q) use ($today, $weekFromNow) {
                        $q->whereRaw("TO_CHAR(birthday, 'MM-DD') >= ?", [$today->format('m-d')])
                          ->whereRaw("TO_CHAR(birthday, 'MM-DD') <= ?", [$weekFromNow->format('m-d')]);
                    });
                } else {
                    // Handle year wrap (December to January)
                    $query->where(function ($q) use ($today, $weekFromNow) {
                        $q->whereRaw("TO_CHAR(birthday, 'MM-DD') >= ?", [$today->format('m-d')])
                          ->orWhereRaw("TO_CHAR(birthday, 'MM-DD') <= ?", [$weekFromNow->format('m-d')]);
                    });
                }
            })
            ->selectRaw("*,
                CASE
                    WHEN TO_CHAR(birthday, 'MM-DD') >= TO_CHAR(NOW(), 'MM-DD')
                    THEN TO_CHAR(birthday, 'MM-DD')
                    ELSE '13-' || TO_CHAR(birthday, 'DD')
                END as birthday_sort")
            ->orderBy('birthday_sort')
            ->limit(5);
    }
}
