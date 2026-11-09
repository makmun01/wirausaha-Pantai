<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = ['destination_id', 'title', 'image_path', 'caption'];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
