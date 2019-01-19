<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Handlers\BusinessPostHandler;
use App\Http\Resources\BusinessPostResource;
use App\Models\Business;
use App\Models\BusinessPost;
use App\Rules\Uuid;
use Illuminate\Http\Request;

class BusinessPostsController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/v1/business-posts",
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
     *         name="text",
     *         in="query",
     *         description="ID of user",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="photo",
     *         in="query",
     *         description="Image encoded in base64",
     *         @OA\Schema(
     *             type="image"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="expire_date",
     *         in="query",
     *         description="I",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="201", description="BusinessPostResource"),
     *   )
     * @param Request $request
     * @param BusinessPostHandler $handler
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, BusinessPostHandler $handler) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid],
            'photo'       => 'required|string',
            'text'        => 'sometimes|string',
            'expire_date' => 'sometimes|string'
        ]);

        $resource = new BusinessPostResource($handler->create($request->business_id, $request));

        return response()->json($resource, 201);
    }


    /**
     *  @OA\Put(
     *     path="/api/v1/business-posts",
     *     
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of business post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
     *         name="text",
     *         in="query",
     *         description="Text of business post",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="expire_date",
     *         in="query",
     *         description="Date",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Business post updated"),
     *     @OA\Response(response="400", description="Invalid given data"),
     *     @OA\Response(response="404", description="Business post not found"),
     *   )
     * @param Request $request
     * @param BusinessPostHandler $handler
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'id'          =>  ['required', new Uuid],
                'business_id'   =>  ['required', 'integer'],
                'text'          =>  'sometimes|string',
                'expire_date'   =>  'sometimes|string'
            ]);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'data' => $request->all()], 400);
        }

        $businessPost = BusinessPost::where('uuid', $request->get('id'))->first();
        if(null === $businessPost) {
            return response()->json(["message" => "business post not found.", 'data' => $request->all()], 404);
        }

        $businessPost->business_id = $request->business_id;
        $businessPost->text = $request->text;
        $businessPost->expire_date = $request->expire_date;

        $businessPost->update();

        return response()->json(new BusinessPostResource($businessPost), 200);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id'  =>  ['required', new Uuid]
        ]);

        $businessPost = BusinessPost::where('uuid', $request->get('id'))->first();
        if(null === $businessPost) {
            return response()->json(["message" => "business post not found.", 'data' => $request->all()], 404);
        }

        $businessPost->delete();
        return response()->json(new BusinessPostResource($businessPost), 200);
    }

    /**
     * 
     * @OA\GET(
     *     path="/api/v1/business-posts",
     *     @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="ID of business",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     
     *   @OA\Response(response="200", description="List of Businesse Post")
     *  )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid]
        ]);

        $businessPosts = BusinessPost::where('business_id', Business::uuid($request->business_id)->id)->paginate();
        return response()->json($businessPosts);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/business-posts/{id}",
     *     
     *     
     *   @OA\Response(response="200", description="Businesse Post")
     * )
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        $post = BusinessPost::uuid($id);
        return response()->json(new BusinessPostResource($post));
    }
}
