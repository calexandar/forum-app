<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\TopicSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TopicSeeder::class);
        $topics = Topic::all();
        
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->withFixtures()
            ->has(Comment::factory(15)->recycle($users))
            ->recycle([$users, $topics])
            ->create();


        User::factory()
            ->has(Post::factory()->count(45)->recycle($topics)->withFixtures())
            ->has(Comment::factory()->count(120)->recycle($posts))
            ->create([
            'name' => 'Aleksandar Cvetanovski',
            'email' => 'admin@gmail.com',
        ]);
    }
}
