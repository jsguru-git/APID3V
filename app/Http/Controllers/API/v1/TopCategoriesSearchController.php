<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BusinessCategoryService;

class TopCategoriesSearchController extends Controller
{
	/**
     * 
     * @OA\Post(
     *     path="/api/v1/top-categories",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword to search",
     *         required=true,
     *         @OA\Schema(
     *             type="string"    
     *         )
     *     ),
     *    
     *     @OA\Response(response="200", description="List results"),
     *
     * )
     * @param Request $request
     * @param BusinessCategoryService $businessCatService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function search(Request $request, BusinessCategoryService $businessCatService) 
    {
    	$this->validate($request, [
    	    'keyword' => 'string|required'
    	]);

    	return response()->json($businessCatService->search($request->get('keyword')));
    }
}
