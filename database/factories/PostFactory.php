<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;


/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    private static Collection $fixtures;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => str(fake()->sentence)->beforeLast('.')->title(),
            'slug' => fake()->unique()->slug(),
            'body' => fake()->realText(200),
        ];
    }

    public function withFixtures()
    {
        $posts = static::getFixtures()
            ->map(fn (string $content) => str($content)->explode("\n", 2))
            ->map((fn (Collection $parts) => [
                'title' => str($parts->first())->trim()->after('#'),
                'body' => str($parts->last())->trim(),
            ]));


        return $this->sequence(...$posts);
    }

    public function getFixtures(): Collection
    {
        return self::$fixtures ??= collect(File::files(database_path('factories/fixtures/posts')))
            ->map(fn (SplFileInfo $file) => $file->getContents());
    }
}
