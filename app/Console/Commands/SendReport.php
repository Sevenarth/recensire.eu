<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Store;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SendReportsController;

class SendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a report to a store';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->ask("Give me store id");
        //$start_date = $this->ask("Give me start date");
        //$end_date = $this->ask("Give me end date");
        $start_date = '2018-08-05 00:00:00';
        $end_date = '2018-08-06 23:59:59';
        
        $store = Store::find($id);
        if($store) {
            SendReportsController::sendReportToStore($store, $start_date, $end_date);
            $this->info("All ok!");
        } else
            $this->error("No store found");
    }
}
