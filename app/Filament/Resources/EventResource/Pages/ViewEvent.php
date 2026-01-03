<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('duplicate')
                ->label('Duplicate')
                ->icon('heroicon-o-document-duplicate')
                ->form([
                    Forms\Components\DatePicker::make('date')
                        ->label('New Event Date')
                        ->required()
                        ->native(false)
                        ->default(now()->addMonth()),
                    Forms\Components\TimePicker::make('time')
                        ->label('Time')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->default($this->record->time),
                ])
                ->action(function (array $data) {
                    $newEvent = $this->record->replicate();
                    $newEvent->date = $data['date'];
                    $newEvent->time = $data['time'];
                    $newEvent->unique_code = null; // Will be auto-generated
                    $newEvent->save();

                    Notification::make()
                        ->title('Event duplicated successfully')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.events.edit', $newEvent);
                }),
            Actions\Action::make('exportAttendees')
                ->label('Export Attendees')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->exportAttendees()),
            Actions\Action::make('viewQR')
                ->label('QR Code')
                ->icon('heroicon-o-qr-code')
                ->url(fn () => route('admin.events.qr', $this->record))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function exportAttendees(): StreamedResponse
    {
        $event = $this->record;

        return response()->streamDownload(function () use ($event) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name',
                'Phone',
                'Gender',
                'Zone',
                'Visit Status',
                'Checked In At',
            ]);

            $event->attendees()->with('member.zone')->orderBy('checked_in_at')->chunk(100, function ($attendees) use ($handle) {
                foreach ($attendees as $attendee) {
                    fputcsv($handle, [
                        $attendee->member?->name ?? $attendee->name,
                        $attendee->member?->phone ?? $attendee->phone,
                        $attendee->member?->gender?->value ?? '',
                        $attendee->member?->location ?? '',
                        $attendee->member?->visit_status ?? '',
                        $attendee->checked_in_at?->format('Y-m-d H:i') ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 'attendees-' . str($event->title)->slug() . '-' . $event->date->format('Y-m-d') . '.csv');
    }
}
