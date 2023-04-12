<?php

namespace Database\Seeders;

use App\Models\Passport\Client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        if (!User::where('email', 'rhondytioco@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Rhon Stratos',
                'email' => 'rhondytioco@gmail.com',
                'password' => Hash::make('admin'),
                'type' => User::TYPES[USER::ADMIN],
            ]);
        }
        if (Client::all()->count() < 1) {
            app('Laravel\Passport\ClientRepository')->create(
                null,
                config('app.front_name'),
                config('app.front_url') . '/auth/callback',
                null,
                false,
                false,
                false
            );
        }
    }
}
