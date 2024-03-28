<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tymon\JWTAuth\Facades\JWTAuth;

class RouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'title' => $this->faker->sentence,
            'category_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'image' => $this->faker->sentence,
            'period' => $this->faker->sentence,
        ];
    }
}
