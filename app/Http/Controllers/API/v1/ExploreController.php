<?php

namespace App\Http\Controllers\API\v1;

use App\Services\BusinessService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Rules\Lat;
use App\Rules\Lng;

class ExploreController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/explore",
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
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     
     *   @OA\Response(response="200", description="List of businesses")
     * )
     * @param Request $request
     * @param BusinessService $businessService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'lat' => ['required', new Lat],
            'lng' => ['required', new Lng]
        ]);

        $businesses = $businessService->get(
            $request->get('lat'),
            $request->get('lng'),
            $request->get('query')
        );

        return BusinessResource::collection($businesses);
    }
}
