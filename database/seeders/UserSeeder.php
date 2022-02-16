<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Doremi',
            'email' => 'doremi@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
