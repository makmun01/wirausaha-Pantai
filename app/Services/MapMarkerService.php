<?php

namespace App\Services;

use App\Models\MapMarker;

class MapMarkerService
{
    public function getActiveMarkers(?string $category = null)
    {
        $query = MapMarker::where('is_active', true);

        if ($category && $category !== 'ALL') {
            $query->where('category', strtoupper($category));
        }

        return $query->get()->map(function ($marker) {
            return [
                'id' => $marker->id,
                'category' => $marker->category,
                'title' => $marker->title,
                'description' => $marker->description,
                'latitude' => (float) $marker->latitude,
                'longitude' => (float) $marker->longitude,
                'icon_type' => $marker->icon_type,
            ];
        });
    }

    public function createMarker(array $data): MapMarker
    {
        return MapMarker::create([
            'category' => $data['category'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'icon_type' => $data['icon_type'] ?? 'default',
            'is_active' => true,
        ]);
    }
}
