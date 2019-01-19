<?php

namespace Tests\Feature\API\BusinessHours;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class UpdateOpenHours extends TestCase
{
   

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateOpenHours()
    {
        $user   = factory(\App\Models\User::class)->create();
        Passport::actingAs($user);

        $businessHour = factory(\App\Models\BusinessHour::class)->create();
        $params       = [
            'open_period_mins'        => '9:00AM',
            'close_period_mins' => $businessHour->close_period_mins,
            'business_id' => $businessHour->business_id,
            'wd_0' => 1
        ];


        $response = $this->json('PUT', "/api/v1/business-hours/{$businessHour->id}", $params);
        $response
            ->assertStatus(200);

        $this->assertDatabaseHas('business_hours', [
            'id'      => $businessHour->id,
            'open_period_mins'    => '9:00am',
            'wd_0' => '1'
        ]);
    }
}
