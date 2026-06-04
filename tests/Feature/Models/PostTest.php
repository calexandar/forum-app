<?php

use App\Models\Post;
use Illuminate\Support\Str;

it('uses title case for titles', function () {
    $post = Post::factory()->create(['title' => 'this is a test title']);

    expect($post->title)->toBe('This Is A Test Title');
});

it('generates a route to the show page', function () {
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route('posts.show', [
        'post' => $post,
        'slug' => Str::slug($post->title),
    ]));
});


it('generates a route to the show page with parameters', function () {
    $post = Post::factory()->create();

    expect($post->showRoute(['foo' => 'bar']))->toBe(route('posts.show', [
        'post' => $post,
        'slug' => Str::slug($post->title),
        'foo' => 'bar',
    ]));
});

it('generates html from markdown body', function () {
    $post = Post::factory()->make(['body' => '# Hello World']);

    $post->save();

    expect($post->html)->toEqual(str($post->body)->markdown());
});