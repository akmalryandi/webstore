<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, HasTags, LogsActivity;
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('cover')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'stock']);
    }

}
