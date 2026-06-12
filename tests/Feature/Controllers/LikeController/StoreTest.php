<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('likes.store'))
        ->assertRedirect(route('login'));
});

it('allows liking a likable', function (Model $likable) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('likes.store', [
            $likable->getMorphClass(),
            $likable->id
        ]))
        ->assertRedirect();

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likable->id,
        'likeable_type' => $likable->getMorphClass(),
    ]);
    
    expect($likable->refresh()->likes_count)->toBe(1);
})->with([
    fn () => Post::factory()->create(),
]);