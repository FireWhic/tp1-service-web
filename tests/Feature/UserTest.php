<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_users(): void
    {
        $this->seed();

        $users = User::all();

        $response = $this->get('/api/users');
        //dd($response);

        for ($i = 0; $i < $users->count(); $i++) {
            $response->assertJsonFragment([
                'first_name' => $users[$i]->first_name,
                'last_name' => $users[$i]->last_name,
                'email' => $users[$i]->email,
                'phone' => $users[$i]->phone
            ]);
        }
        $response->assertStatus(OK);
    }

    public function test_post_user(): void
    {
        $this->seed();

        $json = [
            'first_name' => 'Je',
            'last_name' => 'SuisUnTest',
            'email' => 'oui@gmail.com',
            'phone' => '418-321-3212'
        ];

        $response = $this->postJson('/api/users', $json);

        $response->assertJsonFragment($json);
        $response->assertStatus(CREATED);
        $this->assertDatabaseHas('users', $json);
    }

    public function test_post_user_should_return_422_when_missing_field(): void
    {
        $this->seed();

        $json = [
            'first_name' => 'Je',
            'last_name' => 'SuisUnTest',
            'email' => 'oui@gmail.com'
        ];

        $response = $this->postJson('/api/users', $json);

        $response->assertStatus(INVALID_DATA);
    }

    public function test_patch_user(): void
    {
        $this->seed();

        $response = $this->patchJson("/api/users/1", [
            'first_name' => 'EncoreUnTest'
        ]);

        $response->assertStatus(OK);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'first_name' => 'EncoreUnTest'
        ]);
    }

    public function test_patch_user_should_return_422_when_missing_field(): void
    {
        $this->seed();

        $response = $this->patchJson("/api/users/1", [
            'first_name' => ''
        ]);

        $response->assertStatus(INVALID_DATA);
    }
}
