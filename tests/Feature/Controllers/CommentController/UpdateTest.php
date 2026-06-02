<?php

use App\Models\Comment;
use App\Models\User;

it('requires authentication', function () {
    $comment = Comment::factory()->create();

    $this->put(route('comments.update', $comment))
        ->assertRedirect(route('login'));
});

it('updates a comment', function () {
    $comment = Comment::factory()->create([
        'body' => 'Original comment body',
    ]);

    $this->actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => 'Updated comment body',
        ]);
    $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment body',
        ]);
});


it('redirects to the post show page after updating', function () {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => 'Updated comment body',
        ]);

    $response->assertRedirect($comment->post->showRoute(['post' => $comment->post]));
});

it('redirects to the post show page after updating with page parameter', function () {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), [
            'body' => 'Updated comment body',
        ]);

    $response->assertRedirect($comment->post->showRoute(['post' => $comment->post, 'page' => 2]));
});

it('does not allow updating another user\'s comment', function () {
    $comment = Comment::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->put(route('comments.update', $comment), [
            'body' => 'Updated comment body',
        ])
        ->assertStatus(403);
});

it('validates the request', function ($body) {
    $comment = Comment::factory()->create();

    $this->actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => $body,
        ])
        ->assertInvalid('body');
})->with([
    null,
    '',
    true,
    false,
    123,
    str_repeat('a', 256),
]);