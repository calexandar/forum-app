<?php   

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;


it('should return the correct components', function () {
    $response = $this->get(route('posts.index'));

    $response->assertHasComponent('posts/Index');
});

it('passes the correct data to the view', function () {

    $posts = Post::factory(3)->create();
    
    $posts->load(['user', 'topic']);
    
    $response = $this->get(route('posts.index'));

    $response->assertHasPaginatedResource('posts', PostResource::collection($posts));

});

it('can filter posts by topic', function () {
    $generalTopic = Topic::factory()->create();

    $posts = Post::factory(2)->for($generalTopic)->create();

    $otherPosts = Post::factory(3)->create();
    
    $posts->load(['user', 'topic']);
    
    $response = $this->get(route('posts.index', ['topic' => $generalTopic]));

    $response->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));

});

it('passes topicsto the vuew', function () {
    $topics= Topic::factory(3)->create();

    $response = $this->get(route('posts.index'));

    $response->assertHasResource('topics', TopicResource::collection($topics));
});

it('passes the topic to the view', function () {
    $topic= Topic::factory()->create();

    
    $response = $this->get(route('posts.index', ['topic' => $topic]));

    $response->assertHasResource('selectedTopic', TopicResource::make($topic));

});