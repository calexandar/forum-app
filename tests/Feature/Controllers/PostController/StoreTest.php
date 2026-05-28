<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->postData = [
        'title' => 'Test Post',
        'body' => 'This is a test post.',
    ];
});

it('requires authentication', function () {
    $this->post(route('posts.store', ['post' => 1]))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), $this->postData);


    $this->assertDatabaseHas('posts', [
       ...$this->postData,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page after storing', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), $this->postData);

    $post = Post::latest('id')->first();

    $response->assertRedirect(route('posts.show', $post));
});

it('requires valid title', function ($badTitle) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), [...$this->postData, 'title' => $badTitle]);

    $response->assertInvalid(['title']);
})->with([
    [null],
    [''],
    str_repeat('a', 9),
    str_repeat('a', 121),
]);

it('requires valid body', function ($badBody) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), [...$this->postData, 'body' => $badBody]);

    $response->assertInvalid(['body']);
})->with([
    [null],
    [''],
    str_repeat('a', 10_001),
    str_repeat('a', 99),
]);