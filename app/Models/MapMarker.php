<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    protected $fillable = [
        'category',
        'title',
        'description',
        'latitude',
        'longitude',
        'icon_type',
        'related_entity_type',
        'related_entity_id',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, $category)
    {
        if ($category && $category !== 'ALL') {
            return $query->where('category', $category);
        }
        return $query;
    }
}
