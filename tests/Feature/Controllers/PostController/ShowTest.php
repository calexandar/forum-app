<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Comment;

it('can show a post', function () {
    $post = Post::factory()->create();

    $response = $this->get($post->showRoute());

    $response->assertHasComponent('posts/Show');
});

it('passes the correct data to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

    $response = $this->get($post->showRoute());

    $response->assertHasResource('post', new PostResource($post));
});

it('passes comments to the view', function () {

    $post = Post::factory()->create();

    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');


    $response = $this->get($post->showRoute());

    $response->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});