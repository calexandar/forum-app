<?php

use App\Models\User;

it('requires authentication', function () {
    $this->post(route('posts.store', ['post' => 1]))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $postData = [
        'title' => 'Test Post',
        'body' => 'This is a test post.',
    ];

    $response = $this->post(route('posts.store'), $postData);


    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post',
        'body' => 'This is a test post.',
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page after storing', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $postData = [
        'title' => 'Test Post',
        'body' => 'This is a test post.',
    ];

    $response = $this->post(route('posts.store'), $postData);

    $post = \App\Models\Post::where('title', 'Test Post')->first();

    $response->assertRedirect(route('posts.show', $post));
});