<?php

namespace App\Models;

use Spatie\Tags\Tag as TagsTag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends TagsTag
{
    // public function products()
    // {
    //     return $this->belongsToMany(Product::class);
    // }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }
}
