<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPurchases implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    private ?string $startDate;
    private ?string $endDate;
    private ?string $supplierId;

    public function __construct(?string $startDate, ?string $endDate, ?string $supplierId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->supplierId = $supplierId;
    }


    /**
     * Build the query for the export
     */
    public function query()
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $supplierId = $this->supplierId;

        return PurchaseOrder::query()
            ->withSum('items', DB::raw('price * quantity'))
            ->with('supplier')
            ->withCount('items')
            ->when($startDate, fn($query, $startDate) => $query->whereDate('delivery_date', '>=', $startDate))
            ->when($endDate, fn($query, $endDate) => $query->whereDate('delivery_date', '<=', $endDate))
            ->when($supplierId, fn($query, $supplierId) => $query->where('supplier_id', $supplierId));
    }

    /**
     * Define the headings for the spreadsheet
     */
    public function headings(): array
    {
        return [
            'Order Date',
            'Order Number',
            'Supplier',
            'Total Items',
            'Total Amount'
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
            $row->supplier?->name,
            $row->items_count,
            number_format($row->items_sum_price_quantity)
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
