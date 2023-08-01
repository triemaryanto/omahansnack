<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'image' => 'BsnVCSzna8x21pXT7d4AmUvKCJAzfRvDV6sGFnGu.png',
            'name' => 'Kopi Tubruk',
            'slug' => 'kopi-tubruk',
            'created_at' => '2022-12-30 13:38:32',
            'updated_at' => '2022-12-30 13:38:32'
        ]);
    }
}
