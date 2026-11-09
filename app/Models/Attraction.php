<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attraction extends Model
{
    protected $fillable = ['destination_id', 'name', 'description', 'safety_level', 'cover_image'];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
