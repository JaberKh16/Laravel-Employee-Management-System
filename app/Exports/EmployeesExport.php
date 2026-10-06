<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements
    FromQuery, WithHeadings, WithMapping,
    WithChunkReading, WithStyles, WithTitle
{
    public function __construct(protected $query) {}

    public function query() { return $this->query; }
    public function chunkSize(): int { return 500; }
    public function title(): string { return 'Employees'; }

    public function headings(): array
    {
        return [
            'ID', 'Name', 'Email', 'Designation', 'Department',
            'Employment Type', 'Status', 'Hired', 'Salary', 'Currency',
            'Phone', 'Address', 'Country', 'State', 'City',
        ];
    }

    public function map($employee): array
    {
        $user    = $employee->user;
        $profile = $user?->profile;

        $name = trim(
            ($profile?->first_name ?? '') . ' ' .
            ($profile?->middle_name ?? '') . ' ' .
            ($profile?->last_name ?? '')
        );

        return [
            $employee->id,
            $name !== '' ? $name : ($user?->name ?? ''),
            $user?->email ?? '',
            (string) ($employee->designation ?? ''),
            (string) optional($employee->department)->name,
            (string) ($employee->employment_type ?? ''),
            (string) ($employee->status?->label() ?? ''),
            optional($employee->date_hired)->format('Y-m-d'),
            $employee->basic_salary !== null ? (float) $employee->basic_salary : null,
            (string) ($employee->currency ?? ''),
            (string) ($employee->phone ?? ''),
            (string) ($employee->address ?? ''),
            (string) optional($employee->country)->name,
            (string) optional($employee->state)->name,
            (string) optional($employee->city)->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();

        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
            "A2:O{$highestRow}" => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}