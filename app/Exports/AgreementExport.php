<?php

namespace App\Exports;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class AgreementExport implements FromCollection, ShouldAutoSize, ShouldQueue, WithMapping, WithHeadings, WithEvents
{
    use Queueable;
    use Exportable;

    public function headings(): array
    {
        return [
            'Id',
            'Leasing subject',
            'Contract cost',
            'Payment amount',
            'Total amount',
            'Validity start',
            'Validity end',
        ];
    }

    public function __construct($agreement)
    {
        $this->agreement = $agreement;
    }

    public function collection()
    {
        return $this->agreement;
    }

    public function map($agreement): array
    {
        return [
            $agreement->id,
            $agreement->leasing_subject,
            $agreement->contract_cost,
            $agreement->payment_amount,
            $agreement->total_amount,
            $agreement->validity_start,
            $agreement->validity_end,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ]
                ]);
            }
        ];
    }
}
