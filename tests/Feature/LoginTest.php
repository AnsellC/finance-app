<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_login()
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
            'name' => 'John Doe',
            'password' => bcrypt('12345'),
        ]);

        $result = $this->postJson('/api/auth/login', [
            'email' => 'test@test.com',
            'password' => '12345',
        ]);
        $result->assertStatus(200);
    }
}
