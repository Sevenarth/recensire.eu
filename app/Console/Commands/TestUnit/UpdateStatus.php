<?php

namespace App\Console\Commands\TestUnit;

use Illuminate\Console\Command;

class UpdateStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tus:update {id} {definition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a test unit status definition.';

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
        //
    }
}
