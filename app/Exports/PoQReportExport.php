<?php

namespace App\Exports;

use App\Models\PoQ;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PoQReportExport implements FromCollection, WithMapping, WithHeadings
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return PoQ::with('product') // ambil relasi
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->product->name ?? '-',
            $row->product->unit,
            (int)$row->average_demand,
            (int)$row->demand_per_year,
            'Rp ' . number_format($row->ordering_cost, 0, ',', '.'),
            'Rp ' . number_format($row->unit_price, 0, ',', '.'),
            'Rp ' . number_format($row->holding_cost, 0, ',', '.'),
            $row->eoq,
            $row->poq,
            // $row->calculated_by,
            // $row->notes,
        ];
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Unit',
            'Average Demand',
            'Demand per Year',
            'Ordering Cost',
            'Unit Price',
            'Holding Cost',
            'EOQ',
            'POQ',
            // 'Calculated By',
            // 'Notes'
        ];
    }
}
