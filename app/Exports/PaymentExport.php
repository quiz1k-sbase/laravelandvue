<?php

namespace App\Exports;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentExport implements FromArray, ShouldAutoSize, ShouldQueue
{
    use Queueable;
    use Exportable;

    public array $agreement;
    //private array $payment;


    /*
    * @return \Illuminate\Support\Collection
    */

    /*public function headings(): array
    {
        return [
            'Id',
        ];
    }*/

    public function __construct($agreement)
    {
        $this->agreement = $agreement;
    }

    public function array(): array
    {
        //dd(gettype($this->agreement), gettype($this->payment));
        //dd($this->agreement['LeasingSubject']);

        return $this->agreement;
    }
}
