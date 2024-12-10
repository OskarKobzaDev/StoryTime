<?php

use App\Models\Post;
use App\Models\Topic;
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

    [['topic_id'=>null],'topic_id'],
    [['topic_id'=>-1],'topic_id'],

    [[ 'body' =>  null ], 'body'],
    [[ 'body' =>  1 ], 'body'],
    [[ 'body' =>  2.5 ], 'body'],
    [[ 'body' =>  true ], 'body'],
    [[ 'body' =>  false ], 'body'],
    [[ 'body' =>  str_repeat('a', 99) ], 'body'],
    [[ 'body' =>  str_repeat('a', 10_001) ], 'body'],
]);

beforeEach(function () {
   $this->validData= fn() => [
       'title' => 'Hello World',
       'topic_id' => Topic::factory()->create()->getKey(),
       'body' => str_repeat('a', 150)
   ];
});

it('requires authentication', function (): void {
    post(route('posts.store', Post::factory()->create()))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();

    $data = value($this->validData);

    actingAs($user)
        ->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id,
    ]);
});

it('redirects user to post show page', function () {
    $user = User::factory()->create();
    $data = value($this->validData);
    actingAs($user)
        ->post(route('posts.store'), $data)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('should require valid data', function (array $badData, array|string $errors ) {
    actingAs(User::factory()->create())->post(route('posts.store'), [...value($this->validData), ...$badData ] )
        ->assertInvalid($errors);
})->with('invalid_post_data');

