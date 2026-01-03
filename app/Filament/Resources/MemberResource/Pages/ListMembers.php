<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Member;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
