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

class CountriesExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    WithStyles,
    WithTitle
{
    public function __construct(protected $query) {}

    public function query()
    {
        return $this->query;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function headings(): array
    {
        return [
            'ID', 'Country Name', 'Country Code', 'Status', 'Created At', 'Updated At',
        ];
    }

    public function map($country): array
    {
        return [
            $country->id,
            (string) ($country->name ?? ''),
            strtoupper((string) ($country->country_code ?? '')),
            $this->resolveStatus($country),
            optional($country->created_at)->format('Y-m-d H:i:s'),
            optional($country->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'Countries';
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
            "A2:F{$highestRow}" => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    protected function resolveStatus($country): string
    {
        $status = $country->status ?? null;

        if ($status === null) {
            return '—';
        }

        if (is_object($status) && method_exists($status, 'label')) {
            return $status->label();
        }

        return ucfirst((string) $status);
    }
}