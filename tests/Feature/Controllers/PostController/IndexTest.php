<?php   

use Inertia\Testing\AssertableInertia;


it('should return the correct components', function () {
    $response = $this->get(route('posts.index'));

    $response->assertInertia(fn (AssertableInertia $page) => 
        $page->component('posts/Index', true)
    );
});

it('passes the correct data to the view', function () {
    $response = $this->get(route('posts.index'));

    $response->assertInertia(fn (AssertableInertia $page) => 
        $page->has('posts')
    );
});