<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MapMarkerService;
use Illuminate\Http\Request;

class ApiMapMarkerController extends Controller
{
    protected MapMarkerService $mapService;

    public function __construct(MapMarkerService $mapService)
    {
        $this->mapService = $mapService;
    }

    public function index(Request $request)
    {
        $category = $request->query('category', 'ALL');
        $markers = $this->mapService->getActiveMarkers($category);

        return response()->json([
            'status' => 'success',
            'data' => $markers,
            'meta' => [
                'center' => [-7.228912, 113.538120],
                'default_zoom' => 15,
                'coordinate_system' => 'WGS84',
            ]
        ]);
    }
}
