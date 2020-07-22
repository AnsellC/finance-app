<?php

namespace Tests;

use App\User;

abstract class BaseTest extends TestCase
{
    protected function createTransaction($token, $data = [
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

    protected function loginUser()
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
