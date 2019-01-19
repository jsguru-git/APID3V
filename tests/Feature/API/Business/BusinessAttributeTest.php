<?php

namespace Tests\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;

class BusinessAttributeTest extends TestCase
{

    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        Passport::actingAs($user);

        $optionalAttribute = factory(\App\Models\OptionalAttribute::class)->create();
        $business = factory(\App\Models\Business::class)->create([
            'user_id' => $user->id
        ]);

        $descriptionText = 'test description';

        $params = [
            'business_id' => $business->uuid,
            'optional_attribute_id' => $optionalAttribute->uuid,
            'description' => $descriptionText,
        ];

        $response = $this->json('POST', '/api/v1/user-businesses/optional-attributes', $params);
        $response->assertStatus(200);

        $this->assertDatabaseHas('business_optional_attribute', [
            'business_id' => $business->id,
            'optional_attribute_id' => $optionalAttribute->id,
            'description' => $descriptionText
        ]);
    }

    public function testUpdate()
    {
        $businessOptionalAttribute = factory(\App\Models\BusinessOptionalAttribute::class)->create();
        Passport::actingAs($businessOptionalAttribute->business->user);

        $descriptionNewText = 'test description';

        $params = [
            'business_id' => $businessOptionalAttribute->business->uuid,
            'optional_attribute_id' =>$businessOptionalAttribute->optionalAttribute->uuid,
            'description' => $descriptionNewText,
        ];

        $response = $this->json('PATCH', '/api/v1/user-businesses/optional-attributes', $params);
        $response->assertStatus(200);

        $this->assertDatabaseHas('business_optional_attribute', [
            'business_id' => $businessOptionalAttribute->business_id,
            'optional_attribute_id' => $businessOptionalAttribute->optional_attribute_id,
            'description' => $descriptionNewText,
        ]);
    }

    public function testDelete()
    {
        $businessOptionalAttribute = factory(\App\Models\BusinessOptionalAttribute::class)->create();
        Passport::actingAs($businessOptionalAttribute->business->user);

        $params = [
            'business_id' => $businessOptionalAttribute->business->uuid,
            'optional_attribute_id' => $businessOptionalAttribute->optionalAttribute->uuid,
        ];

        $response = $this->json('DELETE', '/api/v1/user-businesses/optional-attributes', $params);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('business_optional_attribute', [
            'business_id' => $businessOptionalAttribute->business_id,
            'optional_attribute_id' => $businessOptionalAttribute->optional_attribute_id
        ]);
    }
}
