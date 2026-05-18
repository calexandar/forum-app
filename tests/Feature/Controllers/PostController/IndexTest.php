<?php   

use App\Http\Resources\PostResource;
use App\Models\Post;


it('should return the correct components', function () {
    $response = $this->get(route('posts.index'));

    $response->assertHasComponent('posts/Index');
});

it('passes the correct data to the view', function () {

    $posts = Post::factory(3)->create();
    $posts->load('user');
    $response = $this->get(route('posts.index'));

    $response->assertHasPaginatedResource('posts', PostResource::collection($posts));

});