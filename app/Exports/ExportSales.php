<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportSales implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    private ?string $startDate;
    private ?string $endDate;
    private ?string $status;

    public function __construct(?string $startDate, ?string $endDate, ?string $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }


    /**
     * Build the query for the export
     */
    public function query()
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $status = $this->status;

        return SaleOrder::query()
            ->with('customer')
            ->withCount('items')
            ->when($startDate, fn($query, $startDate) => $query->whereDate('order_date', '>=', $startDate))
            ->when($endDate, fn($query, $endDate) => $query->whereDate('order_date', '<=', $endDate))
            ->when($status, fn($query, $status) => $query->where('status', $status));
    }

    /**
     * Define the headings for the spreadsheet
     */
    public function headings(): array
    {
        return [
            'Order Date',
            'Order Number',
            'Customer',
            'Total Items',
            'Total Amount',
            'Status'
        ];
    }

    /**
     * Map the data to export rows
     */
    public function map($row): array
    {
        return [
            $row->order_date,
            $row->invoice_number,
            $row->customer->name,
            $row->items_count,
            $row->total_amount,
            $row->status
        ];
    }

    /**
     * Define the title of the sheet
     */
    public function title(): string
    {
        return 'Sales Report';
    }

    /**
     * Style the sheet
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '085480'
                    ]
                ]
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [

        ];
    }
}
