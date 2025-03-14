<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Creating admin user...');

        $name = $this->ask('Enter your name');
        $email = $this->ask('Enter your email');
        $password = $this->secret('Enter your password');

        $admin = new User();
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = bcrypt($password);
        $admin->type = 'admin';
        $admin->save();

        $this->info('Admin user created successfully!');
    }
}
