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

class StatesExport implements
    FromQuery, WithHeadings, WithMapping,
    WithChunkReading, WithStyles, WithTitle
{
    public function __construct(protected $query) {}

    public function query() { return $this->query; }
    public function chunkSize(): int { return 500; }
    public function title(): string { return 'States'; }

    public function headings(): array
    {
        return ['ID', 'Name', 'State Code', 'Country', 'Description', 'Created At', 'Updated At'];
    }

    public function map($state): array
    {
        return [
            $state->id,
            (string) ($state->name ?? ''),
            strtoupper((string) ($state->state_code ?? '')),
            (string) optional($state->country)->name,
            (string) ($state->description ?? ''),
            optional($state->created_at)->format('Y-m-d H:i:s'),
            optional($state->updated_at)->format('Y-m-d H:i:s'),
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
            "A2:G{$highestRow}" => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}