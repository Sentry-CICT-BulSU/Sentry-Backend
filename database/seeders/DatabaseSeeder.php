<?php

namespace Database\Seeders;

use App\Models\Passport\Client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'first_name' => 'Rhon Stratos',
                'last_name' => 'Stratos',
                'email' => 'rhondytioco@gmail.com',
                'password' => 'admin',
                'type' => User::TYPES[USER::ADMIN],
                'profile_img' => null,
                'position' => 'Admin',
                'college' => 'College of Computer Studies',
                'contact' => fake()->phoneNumber()
            ]);
        }
        if (Client::all()->count() < 1) {
            $client = app('Laravel\Passport\ClientRepository');
            $client->create(
                null,
                config('app.front_name'),
                config('app.front_url') . '/auth/callback',
                null,
                false,
                false,
                false
            );
            $client->create(
                null,
                'localhost',
                'http://localhost:4200' . '/auth/callback',
                null,
                false,
                false,
                false
            );
        }
    }
}
