<?php

namespace App\Exports;

use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseHistoryExport implements FromQuery, ShouldAutoSize, WithStyles, WithMapping, WithHeadings,WithTitle
{
    use Exportable;
    protected ?string $startDate;
    protected ?string $endDate;
    protected ?int $supplierId;
    protected ?int $productId;

    public function __construct($startDate, $endDate, $supplierId, $productId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->supplierId = $supplierId;
        $this->productId = $productId;
    }


    public function query()
    {
        return PurchaseOrderItem::query()
            ->with(['purchaseOrder.supplier', 'product'])
            ->whereHas('purchaseOrder', function (Builder $query) {
                $query->when($this->startDate, function (Builder $query) {
                    $query->whereDate('created_at', '>=', $this->startDate);
                })->when($this->endDate, function (Builder $query) {
                    $query->whereDate('created_at', '<=', $this->endDate);
                })->when($this->supplierId, function (Builder $query) {
                    $query->where('supplier_id', '=', $this->supplierId);
                });
            })
            ->when($this->productId, function (Builder $query) {
                $query->where('product_id', '=', $this->productId);
            });
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'Purchase History';
    }

    public function headings(): array
    {
        return [
            'Date',
            'Voucher Number',
            'Supplier',
            'Product',
            'Quantity',
            'Price'
        ];
    }

    public function map($row): array
    {
        return [
            $row->purchaseOrder->created_at->format('Y-m-d'),
            $row->purchaseOrder->invoice_number,
            optional($row->purchaseOrder->supplier)->name ?? 'N/A',
            $row->product->name,
            number_format($row->quantity, 2),
            number_format($row->price)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(14);
//        $sheet->getStyle('A2:G2')->getFont()->setBold(true)->setSize(12); // Apply to all header rows

        $sheet->getStyle('A1:G' . $sheet->getHighestRow())->getAlignment()->setVertical('center'); // Center vertically
        $sheet->getStyle('A1:G' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center header horizontally

        // Number formatting for quantity and price:
        $sheet->getStyle('E2:E' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0.00'); // Example for quantity
        $sheet->getStyle('F2:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0.00'); // Example for price

    }
}
