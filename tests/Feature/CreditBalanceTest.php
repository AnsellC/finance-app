<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class CreditBalanceTest extends BaseTest
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_check_his_balance()
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
        $result = $this->getJson('/api/balance', [
            'Authorization' => "Bearer {$token}",
        ]);

        $responseData = $result->json();
        $this->assertEquals($data[0]['amount'] + $data[1]['amount'], $responseData['balance']);
    }
}
