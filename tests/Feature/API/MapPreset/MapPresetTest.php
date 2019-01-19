<?php

namespace Tests\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;

class MapPresetTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->make();
        Passport::actingAs($user);

        factory(\App\Models\MapPreset::class)->create();

        $response = $this->json('GET', '/api/v1/map-presets');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'isOpened',
                        'categories'
                    ]
                ]
        ]);
    }
}
