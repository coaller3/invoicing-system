<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('1234'),
            'status' => 'ACTIVE',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '1234',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Login Successfully',
        ]);
        $this->assertAuthenticatedAs($user);
    }

}
