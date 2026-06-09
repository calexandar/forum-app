<?php

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

beforeEach(function () {
    $this->validData = fn() => [
        'title' => 'Test Post',
        'topic_id' => Topic::factory()->create()->getKey(),
        'body' => 'Lorem ipsums s dolor sit amet consectetur adipisicing elit. Officiis, quod? Officia ut similique dolore. Odit perferendis, sunt dolorem fuga eos explicabo soluta labore repudiandae impedit, neque quaerat tempore. Commodi, voluptate? Atque deleniti, rerum ea ullam earum mollitia nisi dolores dolore commodi debitis dolorum qui sequi fugiat? Itaque illo adipisci veritatis maiores nulla reiciendis, inventore aperiam, corporis excepturi ab facilis quam voluptatibus amet dolor cum in, magnam exercitationem nobis optio explicabo sit. Iure sit vitae, ipsa quas sunt asperiores pariatur atque ipsum architecto suscipit nobis error odit doloribus nisi ratione, voluptatum eius minima cumque optio sint praesentium vel ducimus blanditiis? Dolorum.',
    ];
});

it('requires authentication', function () {
    $this->post(route('posts.store', ['post' => 1]))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();
    $data = value($this->validData);
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), $data);


    $this->assertDatabaseHas('posts', [
       ...$data,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page after storing', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), value($this->validData));

    $post = Post::latest('id')->first();

    $response->assertRedirect($post->showRoute());
});

it('requires valid title', function ($badTitle) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), [...value($this->validData), ...$badTitle]);

    $response->assertInvalid(['title']);
})->with([
    [null],
    [''],
    str_repeat('a', 9),
    str_repeat('a', 121),
]);

it('requires valid body', function ($badBody) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('posts.store'), [...value($this->validData), ...$badBody]);

    $response->assertInvalid(['body']);
})->with([
    [null],
    [''],
    str_repeat('a', 10_001),
    str_repeat('a', 99),
]);