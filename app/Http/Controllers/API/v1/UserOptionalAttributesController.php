<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessOptionalAttributeResource;
use App\Models\Business;
use App\Models\OptionalAttribute;
use App\Rules\Uuid;
use Illuminate\Http\Request;

class UserOptionalAttributesController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return BusinessOptionalAttributeResource::collection(OptionalAttribute::all());
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'business_id'     => ['required', 'exists:businesses,uuid', new Uuid],
            'optional_attribute_id' => ['required', 'exists:optional_attributes,uuid'],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        $optionalAttribute = OptionalAttribute::uuid($request->get('optional_attribute_id'));
        $business = Business::uuid($request->get('business_id'));

        if ((int)$business->user_id === (int)$request->user()->id) {
            $business->optionalAttributes()->attach($optionalAttribute, ['description' => $request->input('description')]);
        } else {
            return response()->json([], 403);
        }

        return BusinessOptionalAttributeResource::collection($business->optionalAttributes);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'business_id' => ['required', 'exists:businesses,uuid', new Uuid],
            'optional_attribute_id' => ['required', 'exists:optional_attributes,uuid', new UUid],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        $business = Business
            ::where('uuid', $request->get('business_id'))
            ->whereHas('optionalAttributes', function ($query) use ($request) {
                $query->where('optional_attributes.uuid', $request->get('optional_attribute_id'));
        })->firstOrFail();

        if ((int)$business->user_id === (int)$request->user()->id) {
            $optionalAttribute = OptionalAttribute::uuid($request->get('optional_attribute_id'));
            $business->optionalAttributes()->updateExistingPivot($optionalAttribute, ['description' => $request->input('description')]);
        } else {
            return response()->json([], 403);
        }

        return BusinessOptionalAttributeResource::collection($business->optionalAttributes);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'business_id' => ['required', 'exists:businesses,uuid', new Uuid],
            'optional_attribute_id' => ['required', 'exists:optional_attributes,uuid'],
        ]);

        $optionalAttribute = OptionalAttribute::uuid($request->get('optional_attribute_id'));
        $business = Business::uuid($request->get('business_id'));

        if ((int)$business->user_id === (int)$request->user()->id) {
            $business->optionalAttributes()->detach($optionalAttribute);
        } else {
            return response()->json([], 403);
        }

        return BusinessOptionalAttributeResource::collection($business->optionalAttributes);
    }
}
