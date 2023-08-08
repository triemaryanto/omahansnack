<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'Kopi Kenangan',
            'slug' => 'kopi-kenangan',
            'category_id' => '4',
            'user_id' => '1',
            'content' => '<p><span style="background-color:rgb(255,255,255);color:rgb(77,81,86);">Kopi Kenangan adalah perusahaan yang bergerak di bidang kopi minuman yang turut meramaikan pasar kopi kekinian di Indonesia. Kopi Kenangan dianggap sukses mengisi ceruk kesenjangan harga antara kopi mahal bertaraf peritel internasional dan kopi instan kemasan yang disajikan di warung-warung kopi.</span></p>',
            'image' => '75ao3wmJ1pIhzKpAxcefWNcUiGpnK6A1IrIkS3kL.jpg',
            'description' => 'Kopi Kenangan dianggap sukses mengisi ceruk kesenjangan harga antara kopi mahal.....',
        ]);
    }
}
