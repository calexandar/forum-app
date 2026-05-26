<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CommentController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:255'],
        ]);

       Comment::create([
            ...$data, 
            'post_id' => $post->id, 
            'user_id' => $request->user()->id
            ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Comment added successfully!']);
        
        return to_route('posts.show', $post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:255'],
        ]);

        $comment->update($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Comment updated successfully!']);
        
        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Comment deleted successfully!']);

        return to_route('posts.show', ['post' => $comment->post_id,  'page' =>$request->query('page')]);
    }
}
