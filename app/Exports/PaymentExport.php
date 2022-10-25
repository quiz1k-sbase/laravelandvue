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

class PaymentExport implements FromCollection, ShouldAutoSize, ShouldQueue, WithMapping, WithHeadings, WithEvents
{
    use Queueable;
    use Exportable;

    public function headings(): array
    {
        return [
            'Id',
            'Agreement id',
            'Payment number',
            'Settlement month',
            'Redemption payment',
            'Advance payment amount',
            'Total amount',
        ];
    }

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function collection()
    {
        return $this->payment;
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->agreement_id,
            $payment->payment_number,
            $payment->settlement_month,
            $payment->redemption_payment,
            $payment->advance_payment_amount,
            $payment->total_amount,
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
