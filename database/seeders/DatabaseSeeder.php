<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userList = [
            [
                'name' => 'Ishan',
                'email' => 'test@test.com',
                'password' => Hash::make('123'),
                'contact_no' => '0778094417',
                'user_role' => 1,
            ]
        ];
        DB::table('users')->insert($userList);

        $this->call([
            StoresSeeder::class,
            TimeSlotsSeeder::class,
            BookingsSeeder::class,
        ]);
    }
}
