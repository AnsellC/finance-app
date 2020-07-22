<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_register()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'email@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $result = $this->postJson('/api/register', $userData);


        $result->assertStatus(201);
    }
}
