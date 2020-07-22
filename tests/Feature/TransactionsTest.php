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

    /** @test */
    public function a_user_can_list_all_transactions()
    {
        $data = [
            [
                'label' => 'Transaction 1',
                'amount' => '1000.25',
                'date' => '2020-01-01 17:00',
            ],
            [
                'label' => 'Transaction 2',
                'amount' => '-500.00',
                'date' => '2020-01-02 23:00',
            ],
        ];

        $token = $this->loginUser();
        $this->createTransaction($token, $data[0]);
        $this->createTransaction($token, $data[1]);
        $result = $this->getJson('/api/transactions', [
            'Authorization' => "Bearer {$token}",
        ]);

        $result->assertStatus(200);
        $jsonResult = $result->json()['data'];
        $this->assertEquals(count($data), count($jsonResult));
    }

    /** @test */
    public function a_user_can_delete_a_transaction()
    {
    }

    private function createTransaction($token, $data = [
        'label' => 'Transaction',
        'amount' => '1000.00',
        'date' => '2020-01-01 17:01',
    ])
    {
        return $this->postJson('/api/transactions', [
            'label' => $data['label'],
            'amount' => $data['amount'],
            'date' => $data['date'],
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
