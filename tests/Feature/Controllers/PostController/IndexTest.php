<?php


use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertComponent("Posts/Index");
});

it('passes posts to the view', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->has('posts')
        );
});

it('passes paginated posts to the view', function () {
    $posts = Post::factory()->count(3)->create();

    $posts->load('user');

    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});
