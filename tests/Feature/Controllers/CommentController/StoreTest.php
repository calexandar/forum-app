<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

it('requires authentication', function () {
    $post = Post::factory()->create();

    $response = $this->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment',
    ]);

    $response->assertRedirect(route('login'));
});

it('can store a comment', function () {
   $user = User::factory()->create();
   $post = Post::factory()->create();

   $response = $this->actingAs($user)->post(route('posts.comments.store', $post), [
       'body' => 'This is a comment',
   ]);

   $this->assertDatabaseHas(Comment::class, [
       'user_id' => $user->id,
       'post_id' => $post->id,
       'body' => 'This is a comment',
   ]);
});

it('redirects after storing a comment', function () {
     $user = User::factory()->create();
    $post = Post::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => 'This is a comment',
    ]);

    $response->assertRedirect(route('posts.show', $post));
});

it('requires validation', function ($value) {
    $post = Post::factory()->create();

    $response = $this->actingAs(User::factory()->create())
        ->post(route('posts.comments.store', $post), [
            'body' => $value,
    ]);

    $response->assertInvalid(['body']);
})->with([
    null,
    '',
    true,
    123,
    str_repeat('a', 1001),
]);