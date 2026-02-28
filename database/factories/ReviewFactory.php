<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Rental;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating'=>fake()->numberBetween( 0, 10),
            'comment' =>fake()->text(20),
            'user_id'=> User::inRandomOrder()->value('id'),
            'rental_id'=> Rental::inRandomOrder()->value('id')
        ];
    }
}
