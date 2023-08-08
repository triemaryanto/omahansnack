<?php

namespace Database\Seeders;

use App\Models\Category;
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
        Category::create([
            'image' => 'BsnVCSzna8x21pXT7d4AmUvKCJAzfRvDV6sGFnGu.png',
            'name' => 'Kopi Tubruk',
            'slug' => 'kopi-tubruk',
            'created_at' => '2022-12-30 13:38:32',
            'updated_at' => '2022-12-30 13:38:32'
        ]);
        Category::create([
            'image' => 'bL3UJ4HhYbrwoBb9WbsU0nqjZAKmzOHzGlnMxq3Z.png',
            'name' => 'Kopi Enak',
            'slug' => 'kopi-enak',
            'created_at' => '2022-12-30 13:38:32',
            'updated_at' => '2022-12-30 13:38:32'
        ]);
        Category::create([
            'image' => 'QtOZUVnk3IvdmKnTNF1CLKh7eZyBkc2SCZC9NP1T.png',
            'name' => 'Kopi ABC',
            'slug' => 'kopi-abc',
            'created_at' => '2022-12-30 13:38:32',
            'updated_at' => '2022-12-30 13:38:32'
        ]);
        Category::create([
            'image' => 'xWH5i2nDNk8SWF4RI09DhuofLQMqkkKtZdV4enPd.png',
            'name' => 'Kopi Mantap',
            'slug' => 'kopi-mantap',
            'created_at' => '2022-12-30 13:38:32',
            'updated_at' => '2022-12-30 13:38:32'
        ]);
    }
}
