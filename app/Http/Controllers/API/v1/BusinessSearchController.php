<?php

namespace App\Http\Controllers\API\v1;

use App\Services\BusinessService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessSearchController extends Controller
{
    /**
     *
     *  @OA\GET(
     *     path="/api/v1/business-search",
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
     *   @OA\Response(response="200", description="List businesses"),
     *  )
     * @param Request $request
     * @param BusinessService $businessService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'query' => 'string|required'
        ]);

        return response()->json($businessService->suggest($request->get('query')));
    }
}
