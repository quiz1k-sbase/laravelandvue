<?php

namespace App\Jobs;

use App\Exports\AgreementExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class AgreementsInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $agreements;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agreement)
    {
        $this->agreements = $agreement;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        return Excel::store(new AgreementExport($this->agreements),'public/documents/agreement.xlsx');
    }
}
