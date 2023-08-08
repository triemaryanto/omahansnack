<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'image' => '5k1oi1Nqo2C8IXhrhOumXmahax2SpQz7hMkL16SN.jpg',
            'name' => 'Kopi Rasa',
            'description' => 'testing',
            'price' => '10000',
            'types' => 'Recommended',
            'category_id' => '3'
        ]);
        Product::create([
            'image' => 'c8IqPEhQL0YhWqGNpjjB2SmV13q57piTIwvs26ZA.jpg',
            'name' => 'kopi enak',
            'description' => 'test',
            'price' => '10000',
            'types' => 'Mantap',
            'category_id' => '4'
        ]);
    }
}
