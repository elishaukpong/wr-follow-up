<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Imports\MembersImport;
use App\Models\Event;
use App\Models\Member;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Import Members')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Forms\Components\Placeholder::make('template')
                        ->label('')
                        ->content(new \Illuminate\Support\HtmlString(
                            '<a href="' . route('admin.members.import-template') . '" class="text-primary-600 hover:underline font-medium">Download Excel Template</a>'
                        )),
                    Forms\Components\Select::make('event_id')
                        ->label('Event')
                        ->options(Event::orderByDesc('date')->pluck('title', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\FileUpload::make('file')
                        ->label('Excel File')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $event = Event::findOrFail($data['event_id']);
                    $filePath = storage_path('app/public/' . $data['file']);

                    $import = new MembersImport($event);
                    Excel::import($import, $filePath);

                    $imported = $import->getImportedCount();
                    $skipped = $import->getSkippedCount();
                    $failures = count($import->failures());

                    Notification::make()
                        ->title('Import Complete')
                        ->body("Imported: {$imported} | Skipped: {$skipped} | Failed: {$failures}")
                        ->success()
                        ->send();
                }),
            Actions\Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->export()),
            Actions\CreateAction::make(),
        ];
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Name',
                'Phone',
                'Email',
                'Gender',
                'Zone',
                'Visits',
                'Status',
                'Follow-up Status',
                'Followed Up At',
                'Notes',
                'First Visit',
            ]);

            // Data rows
            Member::with('zone')->orderBy('name')->chunk(100, function ($members) use ($handle) {
                foreach ($members as $member) {
                    fputcsv($handle, [
                        $member->name,
                        $member->phone,
                        $member->email ?? '',
                        $member->gender ?? '',
                        $member->location ?? '',
                        $member->visit_count,
                        $member->visit_status,
                        $member->follow_up_status ?? '',
                        $member->followed_up_at?->format('Y-m-d H:i') ?? '',
                        $member->notes ?? '',
                        $member->created_at->format('Y-m-d'),
                    ]);
                }
            });

            fclose($handle);
        }, 'members-' . now()->format('Y-m-d') . '.csv');
    }
}
