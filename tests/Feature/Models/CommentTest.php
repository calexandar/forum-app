<?php

use App\Models\Comment;

it('generates html from markdown body', function () {
    $comment = Comment::factory()->make(['body' => '# Hello World']);

    $comment->save();

    expect($comment->html)->toEqual(str($comment->body)->markdown());
});