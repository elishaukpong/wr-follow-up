<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use App\Models\Attendee;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'Attendance History';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event.title')
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('event.location')
                    ->label('Location')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('event.date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->dateTime('g:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('viewEvent')
                    ->label('View Event')
                    ->icon('heroicon-o-calendar-days')
                    ->url(fn (Attendee $record) => route('filament.admin.resources.events.edit', $record->event_id)),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('checked_in_at', 'desc');
    }
}
