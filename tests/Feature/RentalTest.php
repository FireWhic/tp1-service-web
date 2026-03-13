<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RentalTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_average_price_rental(): void
    {
        $this->seed();

        $user = User::first();
        $category = Category::first();

        $equipment = Equipment::create([
            'name' => 'Test',
            'description' => 'encore un test et complexe cette fois... comme celui de EquipmentTest',
            'daily_price' => 25.00,
            'category_id' => $category->id
        ]);

        Rental::factory()->create([
            'start_date' => '2012-01-01',
            'end_date' => '2024-01-02',
            'total_price' => 100,
            'user_id' => $user->id,
            'equipment_id' => $equipment->id
        ]);

        Rental::factory()->create([
            'start_date' => '2015-01-03',
            'end_date' => '2023-01-04',
            'total_price' => 200,
            'user_id' => $user->id,
            'equipment_id' => $equipment->id
        ]);

        Rental::factory()->create([
            'start_date' => '2009-01-03',
            'end_date' => '2027-01-04',
            'total_price' => 67,
            'user_id' => $user->id,
            'equipment_id' => $equipment->id
        ]);

        $response = $this->get('/api/rentals?minDate=2011-01-01&maxDate=2025-01-01');

        $response->assertStatus(OK);

        $data = $response->json()['data'];
        $last = $data[count($data) - 1];
        $this->assertEqualsWithDelta(
            150,
            $last['averagePrice'],
            0.01
        );
    }
}
