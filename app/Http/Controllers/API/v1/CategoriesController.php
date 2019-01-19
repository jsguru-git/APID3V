<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Rules\Uuid;

class CategoriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Returns all categories.",
     *     @OA\Parameter(
     *         name="CategoryService",
     *         in="query",
     *         description="CategoryService Object",
     *         required=true,
     *         @OA\Schema(
     *             type="object"
     *         )
     *     ),
     *     @OA\Response(response="200", description="List of all categories")
     *
     * )
     * @param CategoryService $CategoryService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(CategoryService $CategoryService)
    {
        $results = $CategoryService->getActive();
        foreach ($results as $key => $result) {
        	if(trim($result->icon) !== '') {
	        	$result->icon = asset('storage/' . $result->icon);
	        }
        }
        return CategoryResource::collection($results);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create and save a category.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name of the category",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="icon",
     *         in="query",
     *         description="Icon of the category",
     *         required=false,
     *         @OA\Schema(
     *             type="file"
     *         )
     *     ),
     *     @OA\Response(response="201", description="Category created")
     *
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                'name' => ['required', 'string', 'max:10'],
                'icon' => ['nullable']
            ]);
            
        } catch(\Illuminate\Validation\ValidationException $e) {
            $aErrors = array();
            foreach ($e->errors() as $field => $message) {
                $aErrors[] = $message[0];
            }
            return response()->json(['message' => $aErrors, 'data' => $request->all()], 400);
        }

        $icon = null;
        if($request->file('icon') !== null) {
            $request->file('icon')->store('icons');
            $icon = $request->file('icon')->hashName();
        } 

        $category = Category::create(
            [
                'name' => $request->name,
                'icon' => $icon
            ]);

        return response()->json(new CategoryResource($category), 201);
    }


    /**
     * @OA\Put(
     *     path="/api/v1/categories",
     *     summary="Update a category.",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Uuid of the category",
     *         required=true,
     *         @OA\Schema(
     *             type="char(36)"
     *         )
     *     ),*     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="New name for the category",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="icon",
     *         in="query",
     *         description="Icon of the category to replace existing one",
     *         required=false,
     *         @OA\Schema(
     *             type="file"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Category updated"),
     *     @OA\Response(response="400", description="Category not found")
     *
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        try {

            $this->validate($request, [
                'id' => ['required'],
                'name' => ['required'],
                'icon' => ['nullable']
            ],[
                'id.required' => 'id must be given',
                'name' => 'name must be given'
            ]);

        } catch(\Illuminate\Validation\ValidationException $e) {
            $aErrors = array();
            foreach ($e->errors() as $field => $message) {
                $aErrors[] = $message[0];
            }
            return response()->json(['message' => $aErrors, 'data' => $request->all()], 400);
        }

        $category = Category::where('uuid', $request->get('id'))->first();
        if(null === $category) {
            return response()->json(["message" => "category not found.", 'data' => $request->all()], 404);
        }

        $category->name = $request->name;

        if(null !== $request->icon) {
            Storage::disk('public')->delete('icons/'.$category->icon);
            $category->icon = $request->icon;
        }

        $category->update();
        return response()->json(new CategoryResource($category), 200);

    }


    /**
     * @OA\Delete(
     *     path="/api/v1/categories",
     *     summary="Delete a category and its related icon.",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Uuid of the category to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="char(36)"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Category updated"),
     *     @OA\Response(response="400", description="Category not found")
     *
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        try {
            
            $this->validate($request, [
                'id' => ['required'],
            ],[
                'id.required' => 'id must be given'
            ]);

        } catch(\Illuminate\Validation\ValidationException $e) {
            $aErrors = array();
            foreach ($e->errors() as $field => $message) {
                $aErrors[] = $message[0];
            }
            return response()->json(['message' => $aErrors, 'data' => $request->all()], 400);
        }

        $category = Category::where('uuid', $request->get('id'))->first();

        if(null === $category) {
            return response()->json(["message" => "category not found.", 'data' => $request->all()], 404);
        }

        if(null !== $category->icon) {
            Storage::disk('public')->delete('icons/'.$category->icon);
        }

        $category->delete();
        
        return response()->json(new CategoryResource($category), 200);

    }
}
