<?php

use App\Models\User;

it('requires authentication', function () {
    $this->get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('returns the correct commponent', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('posts.create'))
                    ->assertHasComponent('posts/Create');
});