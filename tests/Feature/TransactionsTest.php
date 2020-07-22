<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function a_transaction_can_be_added()
    {
        $token = $this->loginUser();
        $result = $this->createTransaction($token);
        $result->assertStatus(201);
    }

    private function createTransaction($token, $label = 'test label', $amount = '1000.00', $date = '2020-01-01 17:01')
    {
        return $this->postJson('/api/transactions', [
            'label' => $label,
            'amount' => $amount,
            'date' => $date,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);
    }

    private function loginUser()
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

        return $result->json()['access_token'];
    }
}
