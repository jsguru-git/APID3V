<?php

namespace App\Http\Controllers\API\v1;

use App\Elastic\Rules\AggregationRule;
use App\Elastic\Rules\AttributesCountRule;
use App\Models\Business;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Rules\Lat;
use App\Rules\LatLng;
use App\Rules\Lng;
use App\Rules\Uuid;
use App\Services\BusinessService;
use Elasticsearch\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessesController extends Controller
{
    /**
     * @return mixed
     */
    public function geoJson() {
        $fileToDownload = last(explode("/", config('filesystems.geojson_path')));
        return Storage::download($fileToDownload);
    }

    /**
     *  @OA\GET(
     *     path="/api/v1/businesses/stats",
     *     @OA\Parameter(
     *         name="top_left",
     *         in="query",
     *         description="Top Left of location (GPS)",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="bottom_right",
     *         in="query",
     *         description="Bottom right of location (GPS)",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Stats data"),
     *  )
     * @param Request $request
     * @param Client $elasticClient
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function stats(Request $request, Client $elasticClient) {
        $this->validate($request, [
            'top_left'     => ['required', new LatLng],
            'bottom_right' => ['required', new LatLng]
        ]);

        $topLeft     = $request->get('top_left');
        $bottomRight = $request->get('bottom_right');

        if ($topLeft['lat'] <= $bottomRight['lat']) {
            return response()->json([
                'message' => 'The given data is invalid'
            ], 422);
        }

        $response = $elasticClient->search(AggregationRule::buildRule($topLeft, $bottomRight));
        $response = $response['aggregations'];

        $attributes = $elasticClient->search(AttributesCountRule::buildRule($topLeft, $bottomRight));

        return response()->json([
            'totalBusinesses' => $response['total_businesses']['value'],
            'totalImages'     => $response['total_images']['value'],
            'totalReviews'    => $response['total_reviews']['value'],
            'attributes'      => view('partials.attributes', ['attributes' => $attributes['aggregations']])->render()
        ]);
    }

    /**
     *  @OA\GET(
     *     path="/api/v1/businesses",
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Lat location (GPS)",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Lng of location (GPS)",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Lng of location (GPS)",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="map_preset_id",
     *         in="query",
     *         description="Map Preset ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *   @OA\Response(response="200", description="List businesses"),
     *  )
     * @param Request $request
     * @param BusinessService $businessService
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'map_preset_id' => ['sometimes', new Uuid],
            'category_id'   => [new Uuid],
            'lat'           => ['numeric', 'required_with:lng', new Lat],
            'lng'           => ['numeric', 'required_with:lat', new Lng]
        ]);

        $businesses = $businessService->get(
            $request->get('lat'),
            $request->get('lng'),
            $request->get('query'),
            $request->get('category_id'),
            $request->get('map_preset_id')
        );

        return BusinessResource::collection($businesses);
    }

    /**
     *  @OA\GET(
     *     path="/api/v1/businesses/{id}",
     *     
     *   @OA\Response(response="200", description="BusinessResource information")
     *  )
     * @param $id
     * @return BusinessResource
     */
    public function show($id)
    {
        $business = Business::uuid($id);

        return new BusinessResource($business);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/businesses/{id}/avatar",
     *     @OA\Parameter(
     *         name="avatar",
     *         in="query",
     *         description="Avatar",
     *         required=true,
     *     ),
     *     
     *     @OA\Response(response="200", description="Upload successfully"),
     *     @OA\Response(
     *         response="422",
     *         description="Cannot upload avatar"
     *     )
     *
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateAvatar(Request $request, $id) {
        $this->validate($request, [
            'avatar' => ['required']
        ]);

        $business = Business::find($id);
        if($business && $request->avatar) {
            $filename = $business->id.'-'.substr( md5( $business->id . '-' . time() ), 0, 15) . '.jpg'; // for now just assume .jpg : \
            $path = public_path('storage/' . $filename);
            Image::make($request->avatar)->orientate()->fit(500)->save($path);
            $business->avatar = $filename;
            $business->save();
            return response()->json(new BusinessResource($business), 200);
        } else {
            return response('Something wrong', 422);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/v1/businesses/{id}/avatar/delete",
     *     
     *     @OA\Response(response="200", description="delete avatar successfully"),
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function deleteAvatar($id) {
        $business = Business::find($id);
        if($business && !empty($business->avatar)) {

            $path = public_path('storage/' . $filename);
            unlink(storage_path($path));

            $business->avatar = '';
            $business->save();

        }
        return response()->json(new BusinessResource($business), 200);
    }

    /**
     * @OA\POST(
     *     path="/api/v1/businesses",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name of business",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Lat",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Lng",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *   @OA\Response(response="200", description="BusinessResource"),
     *  )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'lat'  => ['required', 'numeric', new Lat],
            'lng'  => ['required', 'numeric', new Lng]
        ]);

        $data            = $request->all();
        $data['user_id'] = Auth::user()->id;
        $business        = Business::create($data);

        return response()->json(new BusinessResource($business), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/businesses",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="uuid of business",
     *         required=true,
     *         @OA\Schema(
     *             type="char(36)"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name of business",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Lat",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Lng",
     *         required=true,
     *         @OA\Schema(
     *             type="float"
     *         )
     *     ),
     *   @OA\Response(response="200", description="Business updated"),
     *   @OA\Response(response="400", description="Business not found"),
     *  )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        try {
            
            $this->validate($request, [
                'id' => ['required', 'max:36'],
                'name' => ['required', 'string', 'max:191'],
                'lat' => ['required', 'numeric', new Lat],
                'lng' => ['required', 'numeric', new Lng]
            ]);

        } catch(\Illuminate\Validation\ValidationException $e) {
            $aErrors = array();
            foreach ($e->errors() as $field => $message) {
                $aErrors[] = $message[0];
            }
            return response()->json(['message' => $aErrors, 'data' => $request->all()], 400);
        }

        $business = Business::where('uuid', $request->get('id'))->first();
        if(null === $business) {
            return response()->json(["message" => "business not found.", 'data' => $request->all()], 404);
        }

        $business->name = $request->name;
        $business->lat = $request->lat;
        $business->lng = $request->lng;

        $business->update();
        return response()->json(new BusinessResource($business), 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/businesses",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="uuid of business to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="char(36)"
     *         )
     *     ),
     *   @OA\Response(response="200", description="Business updated"),
     *   @OA\Response(response="400", description="Business not found"),
     *  )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => ['required']
        ]);

        $business = Business::where('uuid', $request->get('id'))->first();
        if(null === $business) {
            return response()->json(["message" => "business not found.", 'data' => $request->all()], 404);
        }

        $business->delete();
        return response()->json(new BusinessResource($business), 200);
    }
}
