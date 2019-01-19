<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Handlers\BusinessHourHandler;
use App\Http\Resources\BusinessHoursResource;

class BusinessHoursController extends Controller
{
	/**
	 *
	 * @OA\PUT(
	 *     path="/api/v1/business-hours/{id}",
	 *     @OA\Parameter(
	 *         name="open_period_mins",
	 *         description="Start Time",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="close_period_mins",
	 *         description="end time",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *	   @OA\Parameter(
	 *         name="business_id",
	 *         description="ID of business",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),  
	 *	   @OA\Parameter(
	 *         name="wd_0",
	 *         description="Sunday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ),  
	 *	   @OA\Parameter(
	 *         name="wd_1",
	 *         description="Monday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ), 
	 *	   @OA\Parameter(
	 *         name="wd_2",
	 *         description="Tueday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ), 
	 *	   @OA\Parameter(
	 *         name="wd_3",
	 *         description="Wednesday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ), 
	 *	   @OA\Parameter(
	 *         name="wd_4",
	 *         description="Thursday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ), 
	 *	   @OA\Parameter(
	 *         name="wd_5",
	 *         description="Friday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ), 
	 *	   @OA\Parameter(
	 *         name="wd_6",
	 *         description="Saturday",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="boolean"
	 *         )
	 *     ),     
	 *     
	 *   @OA\Response(response="200", description="BusinessHoursResource")
	 * )
	 *
	 * Update the specified resource in storage.
	 */
    public function updateOpenHours(Request $request, $id, BusinessHourHandler $businessHourHandler)
    {
    	$this->validate($request, [
    	    'open_period_mins' => ['required'],
    	    'close_period_mins'  => ['required'],
    	    'business_id' => ['required']
    	]);

    	$transformedBusinessHour = new BusinessHoursResource($businessHourHandler->update($id, $request));

    	return response($transformedBusinessHour, 200);

    }
}
