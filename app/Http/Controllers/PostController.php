<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Scout\Scout;

class PostController extends BaseController
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->authorizeResource(Post::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Topic $topic = null)
    {
        if($request->query('query')){
            $posts = Post::search($request->query('query'))
                ->query(fn(Builder $query) => $query->with(['user','topic']))
                ->when($topic, fn (\Laravel\Scout\Builder $query) => $query->where('topic_id',$topic->id));
        } else {
            $posts = Post::with(['user','topic'])
                ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
                ->latest()
                ->latest('id');
        }

        return Inertia::render('Posts/Index', [
            'posts' => PostResource::collection($posts->paginate()->withQueryString()),
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn () => $topic ? TopicResource::make($topic) : null,
            'query' => $request->query('query'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Posts/Create',[
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','min:10','max:120'],
            'topic_id' => ['required','exists:topics,id'],
            'body' => ['required', 'string','min:100','max:5000'],
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        // posts/{post}/{slug}?page=1
        if(! Str::endsWith($post->showRoute(), $request->path()) ){
            return redirect($post->showRoute($request->query()), status: 301);
        }

        $post->load('user','topic','likes');

        return inertia('Posts/Show', array(
            'post' => fn () => PostResource::make($post)->withLikePermission(),
            'comments' => function () use ($post) {
                $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10));
                $commentResource->collection->transform(fn ($resource) => $resource->withLikePermission());

                return $commentResource;
            },
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return inertia('Posts/Create',[
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'post' => fn () => $post,
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required','string','min:10','max:120'],
            'topic_id' => ['required','exists:topics,id'],
            'body' => ['required', 'string','min:100','max:5000'],
        ]);
        $prevData = $post->only(array_keys($data));
        $diff = array_diff_assoc($data, $prevData);
        if (!empty($diff)) {
            $post->update($diff);
        }
        return redirect($post->showRoute());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect(route('posts.index'));
    }
}
