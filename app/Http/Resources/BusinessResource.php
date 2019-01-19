<?php

namespace App\Http\Resources;

class BusinessResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
                'hours'      => BusinessHoursResource::collection($this->hours),
                'attributes' => BusinessAttributeResource::collection($this->attributes),
                'optional_attributes' => BusinessOptionalAttributeResource::collection($this->optionalAttributes),
                'categories' => BusinessCategoryResource::collection($this->categories),
                'reviews'    => BusinessReviewResource::collection($this->reviews),
                'posts'    => BusinessPostResource::collection($this->posts)
            ]
        );
    }
}
