<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Rules\Uuid;
use App\Services\BusinessService;
use Illuminate\Http\Request;

class DiscoverController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/discover",
     *     @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="Business ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     
     *   @OA\Response(response="200", description="List of businesses")
     *  )
     * @param Request $request
     * @param BusinessService $businessService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'business_id' => ['required', new Uuid]
        ]);

        $results = $businessService->similar($request->business_id);

        return
            response()->json($results);
    }
}