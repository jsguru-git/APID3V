<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use App\Services\FeedService;

class BusinessFeedController extends Controller
{
    /**
     *  @OA\GET(
     *     path="/api/v1/business-feed/{businessId}",
     *     @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="ID of business",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="List of FeedResource"),
     *  )
     * @param FeedService $feedService
     * @param $businessId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function index(FeedService $feedService, $businessId) {
        $feed = $feedService->forBusiness(
            $businessId
        );

        return FeedResource::collection($feed);
    }
}
