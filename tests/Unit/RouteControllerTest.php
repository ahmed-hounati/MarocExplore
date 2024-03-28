<?php

namespace Tests\Unit;

use App\Models\Category;
use Tests\TestCase;
use App\Models\User;
use App\Models\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

class RouteControllerTest extends TestCase
{


    public function testCreateRoute()
    {
        $category = Category::factory()->create();
        $data = [
            'title' => 'Test Route',
            'category_id' => $category->id,
            'image' => 'sample_image.jpg',
            'period' => 'Sample Period',
            'destinations' => [1, 2, 3]
        ];

        $token = JWTAuth::fromUser(User::factory()->create());

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/route/create', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Route created successfully',
            ]);
    }

}
