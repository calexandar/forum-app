<?php   

use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Testing\AssertableInertia;


it('should return the correct components', function () {
    $response = $this->get(route('posts.index'));

    $response->assertInertia(fn (AssertableInertia $inertia) => 
        $inertia->component('posts/Index')
    );
});

it('passes the correct data to the view', function () {

    $posts = Post::factory(3)->create();
    $response = $this->get(route('posts.index'));

    $response->assertHasPaginatedResource('posts', PostResource::collection($posts));

});