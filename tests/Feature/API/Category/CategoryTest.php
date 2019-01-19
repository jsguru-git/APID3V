<?php

namespace Tests\Feature\API\Category;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;

class CategoryTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }


    public function testCategoryIconUploaded()
    {
        $user     = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->make();

        Passport::actingAs($user);
        Storage::fake('public');        

        $params   = [
            'name' => $category->name,
            'icon' => $file = UploadedFile::fake()->image('test.png')
        ];

        $response = $this->json('POST', '/api/v1/categories', $params);
        $response->assertStatus(201);

        $category->uuid = $response->getData()->id;
        $category->icon = $response->getData()->icon;

        // Assert the file was stored...
        Storage::disk('public')->assertExists('icons/'.$file->hashName());

        $this->assertDatabaseHas('categories', [
            'uuid' => $category->uuid,
            'name' => $category->name,
            'icon' => $category->icon,
        ]);

    }

    public function testUpdateCategory()
    {
        $user     = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->make();
    
        Passport::actingAs($user);
        Storage::fake('public');

        $params   = [
            'name' => $category->name,
            'icon' => $file = UploadedFile::fake()->image('test.png')
        ];

        $category->icon = $file->hashName();

        $response = $this->json('POST', '/api/v1/categories', $params);
        $category->uuid = $response->getData()->id;
        $response->assertStatus(201);


        $params = [
            'id' => $category->uuid,
            'name' => $category->name,
            'icon' => $category->icon
        ];

        $response = $this->json('PUT', '/api/v1/categories', $params);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'uuid' => $category->uuid,
            'name' => $category->name,
            'icon' => $category->icon,
        ]);

    }

    public function testDeleteCategory()
    {
        $user     = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->make();
    
        Passport::actingAs($user);
        Storage::fake('public');

        $params   = [
            'name' => $category->name,
            'icon' => $file = UploadedFile::fake()->image('test.png')
        ];

        $category->icon = $file->hashName();

        $response = $this->json('POST', '/api/v1/categories', $params);
        $category->uuid = $response->getData()->id;

        $response->assertStatus(201);

        $params = [
            'id' => $category->uuid,
        ];

        $response = $this->json('DELETE', '/api/v1/categories', $params);
        $response->assertStatus(200);
        Storage::disk('public')->assertMissing('icons'.$file->hashName());

        $this->assertDatabaseMissing('categories', [
            'uuid' => $category->uuid
        ]);

    }
}
