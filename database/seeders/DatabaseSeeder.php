<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        User::factory()->create([
            'name' => 'Not Manager',
            'email' => 'notmanager@mail.com',
        ]);

        User::factory()->create([
            'name' => 'The Manager',
            'email' => 'manager@mail.com',
            'is_manager' => true,
        ]);

        Ticket::factory(100)
            ->recycle($users)
            ->create();
    }
}
