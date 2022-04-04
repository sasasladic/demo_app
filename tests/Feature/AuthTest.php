<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_registration()
    {
        $password = $this->faker->password;
        $body = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post('api/register', $body);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => $body['email']]);

        DB::table('users')->where('email', $body['email'])->delete();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->post('api/login', [
            'email' => 'sasa96.sladic@gmail.com',
            'password' => 'test123',
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fail_login()
    {
        $response = $this->post('api/login', [
            'email' => 'sasa96.sladic@gmail.com',
            'password' => 'fail',
        ]);

        $response->assertStatus(404)->assertJsonFragment(['data' => ['error' => 'Unauthorised']]);
    }
}
