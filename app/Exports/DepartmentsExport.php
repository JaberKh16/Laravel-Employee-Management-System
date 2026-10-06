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

class DepartmentsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    WithStyles,
    WithTitle
{
    public function __construct(protected $query) {}

    public function query() { return $this->query; }
    public function chunkSize(): int { return 500; }

    public function headings(): array
    {
        return ['ID', 'Name', 'Description', 'Floor', 'Status', 'Created At', 'Updated At'];
    }

    public function map($department): array
    {
        return [
            $department->id,
            (string) ($department->name ?? ''),
            (string) ($department->description ?? ''),
            (string) ($department->floor ?? ''),
            $this->resolveStatus($department),
            optional($department->created_at)->format('Y-m-d H:i:s'),
            optional($department->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string { return 'Departments'; }

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
            "A2:G{$highestRow}" => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    protected function resolveStatus($department): string
    {
        $status = $department->status ?? null;

        if ($status === null) return '—';

        if (is_object($status) && method_exists($status, 'label')) {
            return $status->label();
        }

        return ucfirst((string) $status);
    }
}