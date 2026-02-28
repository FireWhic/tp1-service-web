<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Equipment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' =>fake()->date(),
            'end_date' =>fake()->date(),
            'total_price' =>fake()->numberBetween(20, 200),
            //je ne sais pas s'il y avait une meilleure façon, mais c'est celle que j'ai trouvé pour avoir de la
            //diversiter dans les id
            'user_id'=> User::inRandomOrder()->value('id'),
            'equipment_id'=> Equipment::inRandomOrder()->value('id')
        ];
    }
}
