<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

class Topic extends Model
{
    use HasFactory;

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

}
