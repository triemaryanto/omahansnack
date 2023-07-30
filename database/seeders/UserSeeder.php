<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'      => 'Tri Maryanto',
            'email'     => 'admin@app.com',
            'password'  => Hash::make('password'),
            'address'     => 'Jl. Belajar no 717',
            'houseNumber'     => 'Rumah No. 17',
            'phoneNumber'     => '081515815175',
            'city'     => 'Demak',
            'roles'     => 'ADMIN',
        ]);
    }
}
