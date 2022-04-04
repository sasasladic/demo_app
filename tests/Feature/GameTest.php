<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class GameTest extends TestCase
{
    use WithFaker;

    public function test_list_of_games()
    {
        $user = User::latest()->first();

        $response = $this->actingAs($user, 'api')->get('api/game');

        $response->assertStatus(200)->assertJsonFragment(['id' => 1, 'name' => 'Word game']);
    }

    public function test_get_game_fields()
    {
        $user = User::latest()->first();

        $response = $this->actingAs($user, 'api')->get('api/game/fields', ['game' => 'Word game']);

        $response->assertStatus(200)->assertJsonFragment(['type' => 'text', 'name' => 'word']);
    }

    public function test_game_play()
    {
        $user = User::factory()->create();
        $body = [
            'input' => [
                [
                    'name' => 'word',
                    'type' => 'text',
                    'value' => 'array'
                ]
            ],
            'game_id' => 1,
            'game' => 'Word game'
        ];

        $response = $this->actingAs($user, 'api')->post('api/game/play', $body);

        $response->assertStatus(200)->assertJsonFragment(['input' => 'array', 'points' => 5]);
        $this->assertDatabaseHas('user_game', ['user_id' => $user->id, 'input' => 'array', 'points' => 5]);

        $user->delete();
    }

    public function test_already_played_today()
    {
        $user = User::factory()->create();
        $user->games()->attach(1, ['input' => 'something', 'points' => 9, 'created_at' => now()]);

        $body = [
            'input' => [
                [
                    'name' => 'word',
                    'type' => 'text',
                    'value' => 'something'
                ]
            ],
            'game_id' => 1,
            'game' => 'Word game'
        ];

        $response = $this->actingAs($user, 'api')->post('api/game/play', $body);

        $response->assertStatus(403)->assertJsonFragment(['success' => false]);

        $user->delete();
    }
}
