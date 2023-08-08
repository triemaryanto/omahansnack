<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'post_id' => 2,
            'name' => 'hilkia',
            'email' => 'user@app.com',
            'comment' => 'mantap'
        ]);
    }
}
