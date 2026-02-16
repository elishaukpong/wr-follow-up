<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MemberImportTemplate implements FromArray, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return ['Name', 'Phone', 'Email', 'Gender', 'Zone', 'Birthday'];
    }

    public function array(): array
    {
        return [
            ['John Doe', '08012345678', 'john@example.com', 'male', 'Zone A', '1990-05-15'],
            ['Jane Smith', '08098765432', 'jane@example.com', 'female', 'Zone B', '1985-12-20'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
