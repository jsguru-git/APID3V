<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MapPresetResource;
use App\Services\MapPresetService;

class MapPresetsController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/map-presets",
     *     
     *     
     *   @OA\Response(response="200", description="List of MapPresetResource")
     * )
     * @param MapPresetService $mapPresetService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(MapPresetService $mapPresetService)
    {
        $results = $mapPresetService->getActive();
        return MapPresetResource::collection($results);
    }
}
