<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Comment;

use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();

    $response = $this->get($post->showRoute());

    $response->assertHasComponent('posts/Show');
});

it('passes the correct data to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

    $response = $this->get($post->showRoute());

    $response->assertHasResource('post', new PostResource($post));
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    $expectedResource = CommentResource::collection($comments->reverse());
    $expectedResource->collection->transform(fn (CommentResource $resource) => $resource->withLikePermission());

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', $expectedResource);
});

it('redirects to the correct URL if the slug is missing', function (string $slug) {
    $post = Post::factory()->create([
        'title' => 'My First Post',
    ]);

    $response = $this->get(route('posts.show', [$post,  $slug, 'page' => 1]));

    $response->assertRedirect($post->showRoute(['page' => 1]));
})->with([
    'foo-bar',
    'hello'
]);