<?php

namespace App\Filament\Resources;

use App\Enums\EventStatus;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Events';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Event Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->hiddenLabel()
                            ->height(200)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('title')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->html()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('date')
                            ->date('l, F j, Y'),
                        Infolists\Components\TextEntry::make('time')
                            ->time('g:i A'),
                        Infolists\Components\TextEntry::make('location')
                            ->icon('heroicon-o-map-pin'),
                        Infolists\Components\TextEntry::make('capacity')
                            ->placeholder('Unlimited'),
                    ])->columns(2),

                Infolists\Components\Section::make('Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->badge(),
                        Infolists\Components\TextEntry::make('attendees_count')
                            ->label('Total Attendees')
                            ->state(fn (Event $record) => $record->attendees()->count()),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime(),
                    ])->columns(3),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->native(false)
                            ->displayFormat('M d, Y'),
                        Forms\Components\TimePicker::make('time')
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('events')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('capacity')
                            ->numeric()
                            ->label('Capacity (optional)'),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options(EventStatus::class)
                            ->default(EventStatus::Published),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->time('g:i A'),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('attendees_count')
                    ->counts('attendees')
                    ->label('Attendees'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(EventStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('viewQR')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn (Event $record) => route('admin.events.qr', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendeesRelationManager::class,
            RelationManagers\GalleryImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
