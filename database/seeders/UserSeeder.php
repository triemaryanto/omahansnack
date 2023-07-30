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
            'address'     => 'Tawang Sari 01/04 Tawangsari Indah Wonosobo Jateng',
            'houseNumber'     => 'No. i.7',
            'phoneNumber'     => '085157392291',
            'city'     => 'Wonosobo',
            'roles'     => 'ADMIN',
        ]);
    }
}
