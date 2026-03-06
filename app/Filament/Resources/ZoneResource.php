<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZoneResource\Pages;
use App\Jobs\SendSmsBroadcast;
use App\Models\Member;
use App\Models\SmsBroadcast;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ZoneResource extends Resource
{
    protected static ?string $model = Zone::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Zone Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
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
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('members_count')
                    ->counts('members')
                    ->label('Members')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\Action::make('sendSms')
                    ->label('Send SMS')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->form([
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->maxLength(960)
                            ->rows(4)
                            ->helperText('Max 960 characters (6 SMS pages)'),
                    ])
                    ->action(function (Zone $record, array $data): void {
                        $recipientCount = Member::where('zone_id', $record->id)
                            ->whereNotNull('phone')
                            ->where('phone', '!=', '')
                            ->count();

                        if ($recipientCount === 0) {
                            Notification::make()
                                ->title('No recipients')
                                ->body("No members with phone numbers in {$record->name}.")
                                ->danger()
                                ->send();
                            return;
                        }

                        $broadcast = SmsBroadcast::create([
                            'message' => $data['message'],
                            'recipient_type' => 'zone',
                            'zone_id' => $record->id,
                            'recipient_count' => $recipientCount,
                            'status' => 'pending',
                            'sent_by' => auth()->id(),
                        ]);

                        SendSmsBroadcast::dispatch($broadcast);

                        Notification::make()
                            ->title('SMS Queued')
                            ->body("Sending to {$recipientCount} members in {$record->name}.")
                            ->success()
                            ->send();
                    })
                    ->modalHeading(fn (Zone $record) => "Send SMS to {$record->name}")
                    ->modalDescription(fn (Zone $record) => Member::where('zone_id', $record->id)->whereNotNull('phone')->where('phone', '!=', '')->count() . ' members with phone numbers')
                    ->modalSubmitActionLabel('Send'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZones::route('/'),
            'create' => Pages\CreateZone::route('/create'),
            'edit' => Pages\EditZone::route('/{record}/edit'),
        ];
    }
}
