<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();

        $users->each(function ($user) {
            \App\Models\UserLogin::factory(300000)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
