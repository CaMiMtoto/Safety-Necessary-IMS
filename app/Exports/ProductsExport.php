<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Product::with('category')->get();
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->category->name,
            number_format($row->stock_quantity,2),
            number_format($row->price,2),
            $row->unit_measure,
            $row->stock_unit_measure,
            $row->reorder_level,
            $row->stock_quantity <= $row->reorder_level ? "Low Stock" : "In Stock"
        ];
    }

    public function headings(): array
    {
        return [
            "Name",
            "Category",
            "Stock Qty",
            "Price",
            "Selling Measure",
            "Stock Measure",
            "Reorder Level",
            "Status"
        ];
    }

    /*    public function styles(Worksheet $sheet): void
        {
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        }*/
    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // Header row
        $sheet->getStyle('A1:H' . $sheet->getHighestRow())->getAlignment()->setVertical('center'); // Center vertically
        // align price column to right
        $sheet->getStyle('D2:D' . $sheet->getHighestRow())->getAlignment()->setHorizontal('right');

        // Conditional styling for the "Status" column:
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        for ($i = 2; $i <= $lastRow; $i++) {
            $status = $sheet->getCell($lastColumn . $i)->getValue();
            if ($status === 'Low Stock') {
                $sheet->getStyle($lastColumn . $i)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
            }
        }
    }
}
