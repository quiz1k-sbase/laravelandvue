<?php

namespace App\Jobs;

use App\Exports\PaymentExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class PaymentsInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payments;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payments = $payment;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        return Excel::store(new PaymentExport($this->payments),'public/documents/payments.xlsx');
    }
}
