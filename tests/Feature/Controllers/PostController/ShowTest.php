<?php

use App\Http\Resources\PostResource;
use App\Models\Post;

it('can show a post', function () {
    $post = Post::factory()->create();

    $response = $this->get(route('posts.show', $post));

    $response->assertHasComponent('posts/Show');
});

it('passes the correct data to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

    $response = $this->get(route('posts.show', $post));

    $response->assertHasResource('post', new PostResource($post));
});