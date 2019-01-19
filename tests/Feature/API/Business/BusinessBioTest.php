<?php

namespace Tests\Feature\API\Business;

use App\Elastic\Rules\AggregationRule;
use App\Models\MapPreset;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;


class BusinessBioTest extends TestCase
{

    public function testShowBio()
    {
        $user = factory(\App\Models\User::class)->make();
        $customBioText = 'Custom bio text';
        $business = factory(\App\Models\Business::class)->create([
            'bio' => $customBioText
        ]);

        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/business-bio/' . $business->uuid);
        $response
            ->assertStatus(200)
            ->assertJson([
                'bio' => $customBioText
            ]);
    }

    public function testUpdate()
    {
        $user = factory(\App\Models\User::class)->make();
        $business = factory(\App\Models\Business::class)->create();

        $customBioText = 'Custom bio text';

        $params   = [
            'id' => $business->uuid,
            'bio' => $customBioText,
        ];

        Passport::actingAs($user);

        $response = $this->json('PATCH', '/api/v1/business-bio', $params);
        $response->assertStatus(200);

        $this->assertDatabaseHas('businesses', [
            'id' => $business->id,
            'bio' => $customBioText
        ]);
    }

    public function testGenerateBio()
    {
        $user = factory(\App\Models\User::class)->make();
        $business = factory(\App\Models\Business::class)->create([
            'lat' => '42.50729',
            'lng' => '1.53414'
        ]);
        $businessCategory = factory(\App\Models\BusinessCategory::class)->create([
            'business_id' => $business->id
        ]);
        $businessHours = factory(\App\Models\BusinessHour::class)->create([
            'business_id' => $business->id
        ]);

        $bioShouldBeString = $business->name . " is a " . $businessCategory->category->name . " in les Escaldes open 1 days per week";

        $params = [
            'id' => $business->uuid
        ];

        Passport::actingAs($user);

        $updateResponse = $this->json('PATCH', '/api/v1/business-bio', $params);
        $updateResponse->assertStatus(200);

        $showResponse = $this->json('GET', '/api/v1/business-bio/' . $business->uuid);
        $showResponse
            ->assertStatus(200)
            ->assertJson([
                'bio' => $bioShouldBeString
            ]);
    }
}
