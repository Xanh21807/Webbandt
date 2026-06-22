<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class RefactoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Đăng ký thành công',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'user'
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Đăng nhập thành công',
            ])
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'user'
                ]
            ]);
    }

    public function test_get_products_list()
    {
        $category = Category::create([
            'name' => 'iPhone',
            'description' => 'Apple products'
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'iPhone 15 Pro',
            'brand' => 'Apple',
            'price' => 25000000,
            'quantity' => 10,
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'brand',
                            'price',
                            'category',
                            'images',
                            'reviews'
                        ]
                    ]
                ]
            ]);
    }
}
