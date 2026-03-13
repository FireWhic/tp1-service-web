<?php

namespace Tests\Feature;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_delete_review_should_return_404_when_id_not_found(): void
    {
        $this->seed();

        $response = $this->delete('/api/reviews/9999');

        $response->assertStatus(NOT_FOUND);
    }

    public function test_delete_review(): void
    {
        $this->seed();

        $review = Review::factory()->create();

        $response = $this->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(OK);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id
        ]);
    }
}
