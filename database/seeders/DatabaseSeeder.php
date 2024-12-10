<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Topic;
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
        $this->call(TopicSeeder::class);
        $topics = Topic::all();

        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->withFixture()
            ->has(Comment::factory(15)->recycle($users))
            ->recycle([$users, $topics])
            ->create();

        $comments = Comment::factory(150)->recycle($users)->recycle($posts)->create();

        $test = User::factory()
            ->has(Post::factory(2)->withFixture()->recycle($topics)->has(Comment::factory(50)->recycle($users)))
            ->has(Comment::factory(120)->recycle($posts))
            ->create([
            'name' => 'Oskar Kobza',
            'email' => 'test@example.com',
            'password' => '11111111',
        ]);
        $user = User::find(11);
        Post::factory(2)
            ->withFixture()
            ->for($user)
            ->recycle($topics)
            ->create()
            ->each(function ($post) use ($user) {
                Comment::factory(50)->for($post)->for($user)->create();
            });
    }
}
