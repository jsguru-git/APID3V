<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StickerResource;
use App\Models\Sticker;
use Illuminate\Http\Request;

class StickersController extends Controller
{
    /**
     *  @OA\GET(
     *     path="/api/v1/stickers",
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Tags",
     *         in="query",
     *         description="tags",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     
     *     @OA\Response(response="200", description="List of StickerResource")
     *  )    
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request) {
        $this->validate($request, [
            'category_id' => 'sometimes|integer',
            'tags'        => 'sometimes|string'
        ]);

        $stickers   = Sticker::with('categories');
        $categoryId = $request->get('category_id');
        $tags       = $request->get('tags');

        if (null !== $categoryId) {
            $stickers->whereHas('categories', function ($query) use ($categoryId) {
                $query->whereStickerCategoryId($categoryId);
            });
        }

        if (null !== $tags) {
            $tags = explode(",", $tags);
            $stickers->where(function($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhereRaw("FIND_IN_SET('$tag', tags)");
                }
            });
        }

        $stickers = $stickers->paginate();
        return response()->json(new StickerResource($stickers));
    }
}
