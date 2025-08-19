<?php

namespace App\Data;

use App\Models\Tag;
use Spatie\LaravelData\Data;

class ProductCollectionData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $product_count
    ) {}

    public static function fromModel(Tag $tag): self
    {
        return new self(
            $tag->id,
            (String)$tag->name,
            (String)$tag->slug,
            $tag->products()->count()
        );
    }
}
