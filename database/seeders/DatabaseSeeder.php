<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CommentFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->has(Comment::factory(15)->recycle($users))
            ->recycle($users)
            ->create();

        $comments = Comment::factory(150)->recycle($users)->recycle($posts)->create();

        $test = User::factory()
            ->has(Post::factory(2)->has(Comment::factory(7)->recycle($users)))
            ->has(Comment::factory(120)->recycle($posts))
            ->create([
            'name' => 'Oskar Kobza',
            'email' => 'test@example.com',
            'password' => '11111111',
        ]);
    }
}