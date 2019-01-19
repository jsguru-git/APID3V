<?php

namespace Tests\Feature\API\Business;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class TopCategoriesSearchTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSearchCategories()
    {
    	$user = factory(\App\Models\User::class)->make();

    	Passport::actingAs($user);

    	$hosts = [
    	    env('SCOUT_ELASTIC_HOST', 'localhost:9000')
    	];

    	$keyword = "test";

    	/*$elasticClient = ClientBuilder::create()->setHosts($hosts)->build();
    	$search        = $elasticClient->search(BusinessSuggestRule::build($keyword));
    	$search        = $search['aggregations']; */

    	

    	$params = ['keyword' => $keyword];

    	$response = $this->json('GET', '/api/v1/top-categories', $params);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'categories' => []
            ]);
    }
}
