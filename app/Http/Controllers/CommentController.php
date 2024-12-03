<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller as BaseController;

class CommentController extends BaseController
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->authorizeResource(Comment::class);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
           'body' => 'required|string|min:3|max:2500',
        ]);

        Comment::make($validated)
            ->user()->associate($request->user())
            ->post()->associate($post)
            ->save();

        return to_route('posts.show', $post)
            ->banner('Your comment has been posted.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {

        $data = $request->validate([
            'body' => 'required|string|min:3|max:2500',
        ]);

        $comment->update($data);

        return to_route('posts.show',['post'=> $comment->post_id, 'page'=> $request->query('page')])
            ->banner('Your comment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return to_route('posts.show', ['post'=> $comment->post_id, 'page'=> $request->query('page')])
            ->banner('Your comment has been deleted.');
    }
}
