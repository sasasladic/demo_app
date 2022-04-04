<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_history()
    {
        $user = User::factory()->create();
        $i = 0;

        $user->games()->attach([1 => ['input' => 'seeds', 'points' => 5]]);
        while ($i <= 15) {
            $user->games()->attach(
                [1 => ['input' => $this->faker->word, 'points' => $this->faker->numberBetween(1, 10)]]
            );
            $i++;
        }

        $response = $this->actingAs($user, 'api')->get('api/user/history?gameId=1&page=2');

        $response->assertStatus(200)
            ->assertJsonFragment(['input' => 'seeds', 'points' => 5])
            ->assertJson(['meta' => ['current_page' => 2]]);

        $user->delete();
    }
}
