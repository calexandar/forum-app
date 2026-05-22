<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

it('requires authentication', function () {
    $comment = Comment::factory()->create();

    $response = $this->delete(route('posts.comments.destroy', $comment), [
        'body' => 'This is a comment',
    ]);

    $response->assertRedirect(route('login'));
});

it('can delete a comment', function () {
   $comment = Comment::factory()->create();

   $response = $this->actingAs($comment->user)->delete(route('posts.comments.destroy', $comment));

   $this->assertDatabaseMissing(Comment::class, [
       'id' => $comment->id,
   ]);
});

it('redirects to show page after deleting a comment', function () {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($comment->user)->delete(route('posts.comments.destroy', $comment));

    $response->assertRedirect(route('posts.show', $comment->post_id));
});

it('cannot delete another users comment', function () {
    $comment = Comment::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->delete(route('posts.comments.destroy', $comment));

    $response->assertForbidden();
});

it('prevents deleteing a comment posted an hour ago', function () {
    $this->freezeTime();

    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    $response = $this->actingAs($comment->user)->delete(route('posts.comments.destroy', $comment));

    $response->assertForbidden();
});

it('redirects to show page after deleting a comment with page parameter', function () {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($comment->user)->delete(route('posts.comments.destroy', ['comment' => $comment, 'page' => 2]));

    $response->assertRedirect(route('posts.show', ['post' => $comment->post_id, 'page' => 2]));
});