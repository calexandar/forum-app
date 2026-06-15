<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Topic $topic)
    {
        $posts = Post::with(['user', 'topic'])
            ->when($topic->exists, fn (Builder $query) => $query->whereBelongsTo($topic))
            ->when($request->query('query'), 
                fn (Builder $query) => $query->whereAny(['title', 'body'], 'like', '%' . $request->query('query') . '%'))
            ->latest()
            ->paginate();

        return Inertia::render('posts/Index', [
            'posts' => PostResource::collection($posts),
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn () => $topic->exists ? TopicResource::make($topic) : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Post::class);

        return Inertia::render('posts/Create', [
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:10|max:120',
            'topic_id' => 'required|exists:topics,id',
            'body' => 'required|string|min:100|max:10000',
        ]);

        $post = Post::create([
             ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect($post->showRoute(), 301);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        if(!Str::endsWith($post->showRoute(), $request->path())){
            return redirect($post->showRoute($request->query()), 301);
        }

        $post->load('user');

        return Inertia::render('posts/Show', [
            'post' => fn () => PostResource::make($post)->withLikePermission(),
            'comments' => function () use ($post) {        
               $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10));
               $commentResource->collection->transform(fn ($resource) => $resource->withLikePermission());

               return $commentResource;
            },
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        //
    }
}
