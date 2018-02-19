<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user to give panel access';

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
        $name = $this->ask("Choose the account's username");
        $email = $this->ask("Choose the account's email address");
        $password = $this->secret("Choose the account's password");
        $confirm_password = $this->secret("Confirm the account's password");
        if($password == $confirm_password) {
          $password = Hash::make($password);
          $user = new User(compact('name', 'email', 'password'));
          $user->save();
          $this->info("User created with success!");
        } else
          $this->error("The given passwords are not equal!");
    }
}
