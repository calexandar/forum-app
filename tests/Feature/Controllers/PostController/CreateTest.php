<?php

use App\Http\Resources\TopicResource;
use App\Models\Topic;
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

it('passes the correct data to the view', function () {
    $topics = Topic::factory(3)->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('posts.create'))
                    ->assertHasResource('topics', TopicResource::collection(Topic::all()));
});