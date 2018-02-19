<?php

namespace App\Console\Commands\TestUnit;

use Illuminate\Console\Command;

class ListStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tus:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List test units statuses definitions.';

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
      $statuses = config('testUnit.statuses', []);
      $this->info("There are " . count($statuses) . " test unit status definitions.");
      foreach($statuses as $index => $status)
        $this->line("ID: " . $index . ", definition: " . $status);
    }
}
