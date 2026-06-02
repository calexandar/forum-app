<?php

use App\Models\Comment;
use App\Models\User;

it('requires authentication', function () {
    $comment = Comment::factory()->create();

    $response = $this->delete(route('comments.destroy', $comment), [
        'body' => 'This is a comment',
    ]);

    $response->assertRedirect(route('login'));
});

it('can delete a comment', function () {
   $comment = Comment::factory()->create();

   $response = $this->actingAs($comment->user)->delete(route('comments.destroy', $comment));

   $this->assertDatabaseMissing(Comment::class, [
       'id' => $comment->id,
   ]);
});

it('redirects to show page after deleting a comment', function () {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($comment->user)->delete(route('comments.destroy', $comment));

    $response->assertRedirect($comment->post->showRoute(['post' => $comment->post_id]));
});

it('cannot delete another users comment', function () {
    $comment = Comment::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->delete(route('comments.destroy', $comment));

    $response->assertForbidden();
});

it('prevents deleteing a comment posted an hour ago', function () {
    $this->freezeTime();

    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    $response = $this->actingAs($comment->user)->delete(route('comments.destroy', $comment));

    $response->assertForbidden();
});

it('redirects to show page after deleting a comment with page parameter', function () {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($comment->user)->delete(route('comments.destroy', ['comment' => $comment, 'page' => 2]));

    $response->assertRedirect($comment->post->showRoute(['post' => $comment->post_id, 'page' => 2]));
});