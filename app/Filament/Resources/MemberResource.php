<?php

namespace App\Filament\Resources;

use App\Enums\FollowUpStatus;
use App\Enums\Gender;
use App\Enums\ReferralSource;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Attendee;
use App\Models\Member;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\GlobalSearch\Actions\Action;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'People';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phone', 'email'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Phone' => $record->phone,
            'Status' => $record->visit_status,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->tel()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->options(Gender::class),
                        Forms\Components\DatePicker::make('birthday')
                            ->native(false)
                            ->displayFormat('M d, Y'),
                    ])->columns(2),

                Forms\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\Select::make('zone_id')
                            ->label('Zone')
                            ->relationship('zone', 'name', fn ($query) => $query->where('is_active', true))
                            ->searchable()
                            ->preload()
                            ->placeholder('Select a zone or enter custom location below'),
                        Forms\Components\TextInput::make('custom_location')
                            ->label('Custom Location')
                            ->maxLength(255)
                            ->placeholder('If not in a zone'),
                    ])->columns(2),

                Forms\Components\Section::make('Follow-up')
                    ->schema([
                        Forms\Components\Select::make('follow_up_status')
                            ->options(FollowUpStatus::class)
                            ->placeholder('Not set'),
                        Forms\Components\DateTimePicker::make('followed_up_at')
                            ->label('Followed Up At'),
                        Forms\Components\Select::make('referral_source')
                            ->label('How did they hear about us?')
                            ->options(ReferralSource::class)
                            ->placeholder('Not set'),
                    ])->columns(3),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Personal Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('phone')
                            ->icon('heroicon-o-phone'),
                        Infolists\Components\TextEntry::make('email')
                            ->icon('heroicon-o-envelope')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('gender')
                            ->badge()
                            ->placeholder('Not set'),
                        Infolists\Components\TextEntry::make('birthday')
                            ->date('F j')
                            ->icon('heroicon-o-cake')
                            ->placeholder('Not set'),
                    ])->columns(2),

                Infolists\Components\Section::make('Attendance')
                    ->schema([
                        Infolists\Components\TextEntry::make('visit_count')
                            ->label('Total Visits')
                            ->state(fn (Member $record) => $record->visit_count),
                        Infolists\Components\TextEntry::make('visit_status')
                            ->label('Status')
                            ->state(fn (Member $record) => $record->visit_status)
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'First Timer' => 'warning',
                                'Second Timer' => 'info',
                                'Third Timer' => 'primary',
                                'Regular' => 'success',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('location')
                            ->label('Zone/Location')
                            ->state(fn (Member $record) => $record->location)
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('Not set'),
                        Infolists\Components\TextEntry::make('last_attended_at')
                            ->label('Last Attended')
                            ->state(fn (Member $record) => $record->last_attended_at)
                            ->dateTime()
                            ->placeholder('Never'),
                    ])->columns(2),

                Infolists\Components\Section::make('Follow-up Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('follow_up_status')
                            ->label('Status')
                            ->badge()
                            ->placeholder('Not set'),
                        Infolists\Components\TextEntry::make('followed_up_at')
                            ->label('Followed Up At')
                            ->dateTime()
                            ->placeholder('Not yet'),
                        Infolists\Components\TextEntry::make('referral_source')
                            ->label('How they heard about us')
                            ->badge()
                            ->placeholder('Not set'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location')
                    ->label('Zone')
                    ->getStateUsing(fn (Member $record) => $record->location ?? '-')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->whereHas('zone', fn ($zq) => $zq->where('name', 'like', "%{$search}%"))
                              ->orWhere('custom_location', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('visit_count')
                    ->label('Visits')
                    ->getStateUsing(fn (Member $record) => $record->visit_count)
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'gray',
                        $state === 1 => 'warning',
                        $state <= 3 => 'info',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('visit_status')
                    ->label('Status')
                    ->getStateUsing(fn (Member $record) => $record->visit_status)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'gray',
                        'First Timer' => 'warning',
                        'Second Timer' => 'info',
                        'Third Timer' => 'primary',
                        'Regular' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('follow_up_status')
                    ->label('Follow-up')
                    ->badge()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('birthday')
                    ->date('M j')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('zone')
                    ->relationship('zone', 'name'),
                Tables\Filters\SelectFilter::make('gender')
                    ->options(Gender::class),
                Tables\Filters\SelectFilter::make('visit_status')
                    ->label('Status')
                    ->options([
                        'first_timer' => 'First Timer',
                        'regular' => 'Regular',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'first_timer') {
                            return $query->has('attendances', '=', 1);
                        }
                        if ($data['value'] === 'regular') {
                            return $query->has('attendances', '>=', 4);
                        }
                        return $query;
                    }),
                Tables\Filters\SelectFilter::make('follow_up_status')
                    ->label('Follow-up')
                    ->options(FollowUpStatus::class),
                Tables\Filters\Filter::make('needs_follow_up')
                    ->label('Needs Follow-up')
                    ->query(fn ($query) => $query->has('attendances', '=', 1)->whereNull('follow_up_status')),
                Tables\Filters\Filter::make('birthday_this_month')
                    ->label('Birthday This Month')
                    ->query(fn ($query) => $query->whereMonth('birthday', now()->month)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
                    Tables\Actions\BulkAction::make('merge')
                        ->label('Merge Members')
                        ->icon('heroicon-o-arrows-pointing-in')
                        ->requiresConfirmation()
                        ->modalHeading('Merge Members')
                        ->modalDescription('Select the primary member to keep. All attendance records and notes from other members will be transferred to the primary member.')
                        ->form(fn (Collection $records) => [
                            Forms\Components\Select::make('primary_member_id')
                                ->label('Keep this member (primary)')
                                ->options($records->pluck('name', 'id'))
                                ->required()
                                ->helperText('Other selected members will be merged into this one and deleted.'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $primaryId = $data['primary_member_id'];
                            $primary = Member::find($primaryId);
                            $others = $records->filter(fn ($m) => $m->id !== $primaryId);

                            DB::transaction(function () use ($primary, $others) {
                                foreach ($others as $other) {
                                    // Transfer attendances
                                    Attendee::where('member_id', $other->id)
                                        ->update(['member_id' => $primary->id]);

                                    // Transfer notes
                                    Note::where('member_id', $other->id)
                                        ->update(['member_id' => $primary->id]);

                                    // Fill in missing data on primary
                                    if (!$primary->email && $other->email) {
                                        $primary->email = $other->email;
                                    }
                                    if (!$primary->gender && $other->gender) {
                                        $primary->gender = $other->gender;
                                    }
                                    if (!$primary->birthday && $other->birthday) {
                                        $primary->birthday = $other->birthday;
                                    }
                                    if (!$primary->zone_id && $other->zone_id) {
                                        $primary->zone_id = $other->zone_id;
                                    }

                                    // Delete the other member
                                    $other->delete();
                                }

                                $primary->save();
                            });

                            Notification::make()
                                ->title('Members merged successfully')
                                ->body($others->count() . ' member(s) merged into ' . $primary->name)
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendancesRelationManager::class,
            RelationManagers\NotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
