<?php

use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

dataset(name: 'invalid_post_data', dataset: [
    [[ 'title' =>  null ], 'title'],
    [[ 'title' =>  1 ], 'title'],
    [[ 'title' =>  2.5 ], 'title'],
    [[ 'title' =>  true ], 'title'],
    [[ 'title' =>  false ], 'title'],
    [[ 'title' =>  str_repeat('a', 9) ], 'title'],
    [[ 'title' =>  str_repeat('a', 121) ], 'title'],

    [[ 'body' =>  null ], 'body'],
    [[ 'body' =>  1 ], 'body'],
    [[ 'body' =>  2.5 ], 'body'],
    [[ 'body' =>  true ], 'body'],
    [[ 'body' =>  false ], 'body'],
    [[ 'body' =>  str_repeat('a', 99) ], 'body'],
    [[ 'body' =>  str_repeat('a', 10_001) ], 'body'],
]);

beforeEach(function () {
   $this->validData=[
       'title' => 'Hello World',
       'body' => str_repeat('a', 100)
   ];
});

it('requires authentication', function (): void {
    post(route('posts.store', Post::factory()->create()))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->validData);

    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id,
    ]);
});

it('redirects user to post show page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('should require valid data', function (array $badData, array|string $errors ) {
    actingAs(User::factory()->create())->post(route('posts.store'), [Post::latest('id')->first(), ...$badData ] )
        ->assertInvalid($errors);
})->with('invalid_post_data');

