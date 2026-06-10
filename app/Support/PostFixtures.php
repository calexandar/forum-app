<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Facades\File;

class PostFixtures
{

    public static function getFixtures(): Collection
    {
        return once(fn () => collect(File::files(database_path('factories/fixtures/posts')))
            ->map(fn (SplFileInfo $file) => $file->getContents())
            ->map(fn (string $content) => str($content)->explode("\n", 2))
            ->map((fn (Collection $parts) => [
                'title' => str($parts->first())->trim()->after('#'),
                'body' => str($parts->last())->trim(),
            ])));

    }
}