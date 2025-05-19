<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Sales;
use Carbon\Carbon;

class SalesExport implements  FromCollection, WithMapping, WithHeadings
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function collection()
    {
        return Sales::whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->invoice_number,
            Carbon::parse($row->transaction_date)->format('d-m-Y'),
            'Rp ' . number_format($row->total_amount, 0, ',', '.'),
        ];
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Transaction Date',
            'Total Amount',
        ];
    }
}
