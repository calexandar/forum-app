<?php

use App\Models\Post;

it('uses title case for titles', function () {
    $post = Post::factory()->create(['title' => 'this is a test title']);

    expect($post->title)->toBe('This Is A Test Title');
});