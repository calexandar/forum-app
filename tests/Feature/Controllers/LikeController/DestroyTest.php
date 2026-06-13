<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    delete(route('likes.destroy',['post', 1]))
        ->assertRedirect(route('login'));
});

it('allows unliking a likable', function (Model $likable) {
    $user = User::factory()->create();
    $like = Like::factory()->for($user)->for($likable, 'likeable')->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->delete(route('likes.destroy', [
            $likable->getMorphClass(),
            $likable->id
        ]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseEmpty(Like::class);
    
    expect($likable->refresh()->likes_count)->toBe(0);
})->with([
    fn () => Post::factory()->create(['likes_count' => 1]),
    fn () => Comment::factory()->create(['likes_count' => 1]),
]);

it('prevents unliking the same likeable twice', function () {
    $likeable = Post::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route('likes.destroy', [
            $likeable->getMorphClass(),
            $likeable->id
        ]))
        ->assertForbidden();
});

it('only allows unliking supporting models', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [
            $user->getMorphClass(),
            $user->id
        ]))
        ->assertForbidden();
});  


it('throws 404 if the type is not supported', function () {
    actingAs(User::factory()->create())
        ->delete(route('likes.destroy', [
            'foo',
            1,
        ]))
        ->assertNotFound();
});

