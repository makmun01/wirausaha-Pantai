<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\Attraction;
use App\Models\Facility;
use App\Models\Gallery;

class TourismService
{
    public function getJumiangProfile(): ?Destination
    {
        return Destination::with(['attractions', 'facilities', 'galleries'])->first();
    }

    public function getAttractions()
    {
        return Attraction::all();
    }

    public function getFacilities()
    {
        return Facility::all();
    }
}
