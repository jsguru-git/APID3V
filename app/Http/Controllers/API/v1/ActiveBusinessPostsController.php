<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Resources\BusinessPostResource;
use App\Models\Business;
use App\Models\BusinessPost;
use App\Http\Controllers\Controller;
use App\Rules\Uuid;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActiveBusinessPostsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/active-business-posts",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="email"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successfully"),
     *     @OA\Response(
     *         response="422",
     *         description="Password missmatch"
     *     )
     *
     * )
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid]
        ]);

        $businessPosts = BusinessPost::where('business_id', Business::uuid($request->business_id)->id)
                            ->where(function ($query) {
                                $query->where('expire_date', '>=', Carbon::now())
                                    ->orWhereNull('expire_date');
                            })
                            ->paginate();

        return BusinessPostResource::collection($businessPosts);
    }
}
