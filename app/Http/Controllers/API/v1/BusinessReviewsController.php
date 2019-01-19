<?php

namespace App\Http\Controllers\API\v1;

use App\Rules\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Handlers\BusinessReviewHandler;
use App\Http\Resources\BusinessReviewResource;

class BusinessReviewsController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/v1/business-reviews",
     *     
     *     @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="ID of business",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         description="code",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="comment",
     *         in="query",
     *         description="Comment",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="photo",
     *         in="query",
     *         description="Photo",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="201", description="BusinessPostResource"),
     *  )
     */
    public function store(Request $request, BusinessReviewHandler $reviewHandler) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid],
            'code'        => 'required|integer',
            'comment'     => 'sometimes|string',
            'photo'       => 'sometimes|string'
        ]);

        $businessId = $request->business_id;
        $transformedReview = new BusinessReviewResource($reviewHandler->create($businessId, $request));

        return response($transformedReview, 201);
    }

}
