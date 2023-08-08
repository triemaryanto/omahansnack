<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      => 'Tri Maryanto',
            'email'     => 'admin@app.com',
            'password'  => Hash::make('password'),
            'address'     => 'Tawang Sari 01/04 Tawangsari Indah Wonosobo Jateng',
            'houseNumber'     => 'No. i.7',
            'phoneNumber'     => '085157392291',
            'city'     => 'Wonosobo',
            'roles'     => 'ADMIN',
        ])->assignRole('admin')->givePermissionTo(['home','dashboard','master']);
        User::create([
            'name'      => 'Hilkia Yunika',
            'email'     => 'user@app.com',
            'password'  => Hash::make('password'),
            'address'     => 'Tawang Sari 01/04 Tawangsari Indah Wonosobo Jateng',
            'houseNumber'     => 'No. i.7',
            'phoneNumber'     => '085157392291',
            'city'     => 'Wonosobo',
            'roles'     => 'ADMIN',
        ])->assignRole('user')->givePermissionTo(['home','dashboard']);
    }
}
