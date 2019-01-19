<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BusinessBioGeneratorService;

class BusinessBioController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bio = Business::uuid($id)->bio;

        return response()->json([
            'bio' => $bio ?? null,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id  = $request->id;
        $bio = $request->bio;

        $business = Business::uuid($id);

        if(empty($bio)){
            $bio = BusinessBioGeneratorService::generateBio($business);
        }

        $business->bio = $bio;

        $business->save();

        return response('Success', 200);
    }
}
