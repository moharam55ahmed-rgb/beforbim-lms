<?php

namespace App\Modules\Media\Traits;

use App\Modules\Media\Models\MediaFile;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasMedia
{
    public function mediaFiles(): MorphMany
    {
        return $this->morphMany(MediaFile::class, 'model');
    }

    public function media(string $collection = 'default'): MorphMany
    {
        return $this->mediaFiles()->where('collection_name', $collection);
    }

    public function singleMedia(string $collection = 'default'): MorphOne
    {
        return $this->morphOne(MediaFile::class, 'model')->where('collection_name', $collection)->latestOfMany();
    }
}
