<?php

use App\Models\Post;
use Illuminate\Support\Str;

it('uses title case for titles', function () {
    $post = Post::factory()->create([
        'title' => 'Test title hello world',
    ]);

    expect($post->title)->toBe('Test Title Hello World');
});

it('can generate a route to show page', function () {
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route('posts.show', [$post, Str::slug($post->title)]));
});

it('can generate additional query parameters on the show route', function () {
    $post = Post::factory()->create();

    expect($post->showRoute(['page'=>2]))->toBe(route('posts.show', [$post, Str::slug($post->title),'page'=>2]));
});
