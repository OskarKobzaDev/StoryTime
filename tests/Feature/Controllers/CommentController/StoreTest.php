<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('can store a comment', function () {
   $user = User::factory()->create();

   $post = Post::factory()->create();

   actingAs($user)->post(route('posts.comments.store',$post), [
       'body' => 'This is a comment',
   ]);

   $this->assertDatabaseHas(Comment::class, [
       'body' => 'This is a comment',
       'post_id' => $post->id,
       'user_id' => $user->id,
   ]);
});
it('redirects user to the post show page', function () {

    $post = Post::factory()->create();

    actingAs(User::factory()->create())
        ->post(route('posts.comments.store',$post), [
            'body' => 'This is a comment',
        ])
        ->assertRedirect($post->showRoute());
});

it('validates comment to have specific body', function ($value) {
    $post = Post::factory()->create();

    actingAs(User::factory()->create())
        ->post(route('posts.comments.store',$post), [
            'body' => $value,
        ])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a',2501),
    'aa'
]);

it('requires authentication', function () {

    post(route('posts.comments.store', Post::factory()->create()))
        ->assertRedirect(route('login'));

});
