<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Attendee;
use App\Models\Member;
use App\Models\Note;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'Attendees';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('member.phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('member.location')
                    ->label('Zone/Location')
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
                    ->dateTime('g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('zone')
                    ->label('Zone')
                    ->options(Zone::where('is_active', true)->pluck('name', 'id'))
                    ->query(fn ($query, array $data) =>
                        $data['value']
                            ? $query->whereHas('member', fn ($q) => $q->where('zone_id', $data['value']))
                            : $query
                    ),
                Tables\Filters\SelectFilter::make('visit_status')
                    ->label('Status')
                    ->options([
                        'first_timer' => 'First Timer',
                        'returning' => 'Returning',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'first_timer') {
                            return $query->whereHas('member', fn ($q) => $q->has('attendances', '=', 1));
                        }
                        if ($data['value'] === 'returning') {
                            return $query->whereHas('member', fn ($q) => $q->has('attendances', '>', 1));
                        }
                        return $query;
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('addExistingMember')
                    ->label('Add Existing Member')
                    ->icon('heroicon-o-user-plus')
                    ->form([
                        Forms\Components\Select::make('member_id')
                            ->label('Select Member')
                            ->options(function () {
                                $eventId = $this->getOwnerRecord()->id;
                                return Member::whereDoesntHave('attendances', fn ($q) => $q->where('event_id', $eventId))
                                    ->get()
                                    ->mapWithKeys(fn ($m) => [$m->id => "{$m->name} ({$m->phone})"]);
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $member = Member::find($data['member_id']);
                        $event = $this->getOwnerRecord();

                        Attendee::create([
                            'event_id' => $event->id,
                            'member_id' => $member->id,
                            'name' => $member->name,
                            'phone' => $member->phone,
                            'checked_in_at' => now(),
                        ]);

                        Notification::make()
                            ->title("{$member->name} added to event")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('addNewMember')
                    ->label('Add New Member')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('zone_id')
                            ->label('Zone')
                            ->options(Zone::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select zone or enter custom location'),
                        Forms\Components\TextInput::make('custom_location')
                            ->label('Custom Location')
                            ->maxLength(255)
                            ->placeholder('If not in a zone'),
                    ])
                    ->action(function (array $data) {
                        $event = $this->getOwnerRecord();
                        $phone = preg_replace('/[\s\-]/', '', $data['phone']);

                        // Check if member already exists
                        $member = Member::where('phone', $phone)->first();

                        if ($member) {
                            // Check if already attending this event
                            $existing = Attendee::where('event_id', $event->id)
                                ->where('member_id', $member->id)
                                ->exists();

                            if ($existing) {
                                Notification::make()
                                    ->title('Member already checked in')
                                    ->warning()
                                    ->send();
                                return;
                            }
                        } else {
                            // Create new member
                            $member = Member::create([
                                'name' => $data['name'],
                                'phone' => $phone,
                                'zone_id' => $data['zone_id'] ?: null,
                                'custom_location' => $data['zone_id'] ? null : $data['custom_location'],
                            ]);
                        }

                        Attendee::create([
                            'event_id' => $event->id,
                            'member_id' => $member->id,
                            'name' => $data['name'],
                            'phone' => $phone,
                            'checked_in_at' => now(),
                        ]);

                        Notification::make()
                            ->title("{$data['name']} added to event")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('viewMember')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Attendee $record) => $record->member_id
                        ? route('filament.admin.resources.members.view', $record->member_id)
                        : null)
                    ->visible(fn (Attendee $record) => $record->member_id !== null),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove selected'),
                ]),
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->poll('30s');
    }
}
