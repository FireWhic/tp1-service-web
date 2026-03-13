<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\CategoriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_equipment(): void
    {
        $this->seed();

        $equipment = Equipment::all();

        $response = $this->get('/api/equipment');
        //dd($response);

        for ($i = 0; $i < $equipment->count(); $i++) {
            $response->assertJsonFragment([
                'name' => $equipment[$i]->name,
                'description' => $equipment[$i]->description,
                'daily_price' => $equipment[$i]->daily_price,
                'category_id' => $equipment[$i]->category_id
            ]);
        }
        $response->assertStatus(OK);
    }

    public function test_get_equipment_should_return_404_when_id_not_found(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment/9999');

        $response->assertStatus(NOT_FOUND);
    }
    public function test_get_popularity_equipment(): void
    {
        $this->seed();

        $user = User::first();
        $category = Category::first();

        $equipment = Equipment::create([
            'name' => 'Test',
            'description' => 'encore un test et complexe cette fois...',
            'daily_price' => 25.00,
            'category_id' => $category->id
        ]);

        $rental1 = Rental::factory()->create([
            'equipment_id' => $equipment->id,
            'user_id' => $user->id
        ]);

        $rental2 = Rental::factory()->create([
            'equipment_id' => $equipment->id,
            'user_id' => $user->id
        ]);

        Review::factory()->create([
            'rating' => 4,
            'user_id' => $user->id,
            'rental_id' => $rental1->id
        ]);

        Review::factory()->create([
            'rating' => 2,
            'user_id' => $user->id,
            'rental_id' => $rental2->id,
        ]);

        $response = $this->get('/api/equipment/popularity');

        $response->assertStatus(OK);

        $last = $response[count($response->json()) - 1];

        //aidé par l'AI, car sinon la réponse retourné était 2.4000000000000004 et non 2.4
        $this->assertEqualsWithDelta(
            2.4,
            $last['popularity'],
            0.01
        );
    }
}
