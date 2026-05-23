<?php

use App\Models\Comment;

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