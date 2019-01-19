<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use App\Rules\Lat;
use App\Rules\Lng;
use App\Services\FeedService;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/feed",
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Lat of location",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Lng of location",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     
     *   @OA\Response(response="200", description="List of FeedResource")
     *  )
     */
    public function index(Request $request, FeedService $feedService) {
        $this->validate($request, [
            'lat' => ['required', new Lat],
            'lng' => ['required', new Lng]
        ]);

        $feed = $feedService->get(
            $request->lat,
            $request->lng
        );

        return FeedResource::collection($feed);
    }
}
