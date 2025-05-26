<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class PurchaseExport implements FromCollection, WithMapping, WithHeadings
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function collection()
    {
        return PurchaseOrder::with(['supplier', 'items'])
            ->where('status','completed')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();
    }

    public function map($po): array
    {
        $total = $po->items->sum('price');

        return [
            $po->po_number,
            optional($po->supplier)->name,
            optional($po->created_at)->format('d-m-Y'),
            'Rp ' . number_format($total, 0, ',', '.'),
        ];
    }


    public function headings(): array
    {
        return [
            'PO Number',
            'Supplier Name',
            'Order Date',
            'Total Amount',
        ];
    }
}
