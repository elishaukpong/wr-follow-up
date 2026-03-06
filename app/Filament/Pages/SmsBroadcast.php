<?php

namespace App\Filament\Pages;

use App\Jobs\SendSmsBroadcast;
use App\Models\Member;
use App\Models\SmsBroadcast as SmsBroadcastModel;
use App\Services\Sms\SmsService;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class SmsBroadcast extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'SMS Broadcast';
    protected static string $view = 'filament.pages.sms-broadcast';

    public ?string $recipient_type = 'all';
    public ?string $zone_id = null;
    public ?string $message = '';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Compose Message')
                    ->schema([
                        Select::make('recipient_type')
                            ->label('Send To')
                            ->options([
                                'all' => 'All Members',
                            ])
                            ->default('all')
                            ->required()
                            ->live(),
                        Textarea::make('message')
                            ->required()
                            ->maxLength(960)
                            ->rows(4)
                            ->helperText(fn ($state) => strlen($state ?? '') . '/960 characters (max 6 SMS pages)'),
                    ]),
            ]);
    }

    public function getRecipientCount(): int
    {
        $query = Member::whereNotNull('phone')->where('phone', '!=', '');

        return $query->count();
    }

    public function getCredits(): ?int
    {
        return app(SmsService::class)->getCredits();
    }

    public function send(): void
    {
        $this->validate([
            'message' => 'required|string|max:960',
        ]);

        $recipientCount = $this->getRecipientCount();

        if ($recipientCount === 0) {
            Notification::make()
                ->title('No recipients found')
                ->body('There are no members with phone numbers.')
                ->danger()
                ->send();
            return;
        }

        $broadcast = SmsBroadcastModel::create([
            'message' => $this->message,
            'recipient_type' => 'all',
            'zone_id' => null,
            'recipient_count' => $recipientCount,
            'status' => 'pending',
            'sent_by' => auth()->id(),
        ]);

        SendSmsBroadcast::dispatch($broadcast);

        $this->message = '';

        Notification::make()
            ->title('SMS Broadcast Queued')
            ->body("Sending to {$recipientCount} members.")
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(SmsBroadcastModel::query()->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                TextColumn::make('message')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->message),
                TextColumn::make('recipient_type')
                    ->label('Sent To')
                    ->formatStateUsing(fn ($record) => $record->recipient_type === 'all'
                        ? 'All Members'
                        : ($record->zone?->name ?? 'Zone')),
                TextColumn::make('recipient_count')
                    ->label('Recipients')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('sender.name')
                    ->label('Sent By'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25]);
    }
}
