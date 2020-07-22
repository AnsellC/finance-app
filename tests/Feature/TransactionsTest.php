<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;

class TransactionsTest extends BaseTest
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
        $this->assertSameSize($data, $jsonResult);
    }

    /** @test */
    public function a_user_can_delete_a_transaction()
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

        $result = $this->deleteJson('/api/transactions/1', [
            'Authorization' => "Bearer {$token}",
        ]);

        $result->assertStatus(204);

        $result = $this->getJson('/api/transactions', [
            'Authorization' => "Bearer {$token}",
        ]);

        $result->assertStatus(200);
        $jsonResult = $result->json()['data'];
        $this->assertCount(1, $jsonResult);
    }

    /** @test */
    public function a_user_can_view_a_single_transaction()
    {
        $data = [
            'label' => 'My Transaction',
            'amount' => '500.00',
            'date' => '2020-01-01 12:00',
        ];

        $token = $this->loginUser();
        $this->createTransaction($token, $data);

        $response = $this->getJson('/api/transactions/1');
        $response->assertStatus(200);
        $responseData = $response->json()['data'];

        $this->assertEquals($data['label'], $responseData['label']);
        $this->assertEquals($data['amount'], $responseData['amount']);
    }

    /** @test */
    public function a_user_can_edit_a_transaction()
    {
        $this->withoutExceptionHandling();
        $token = $this->loginUser();
        $this->createTransaction($token, [
            'label' => 'My Transaction',
            'amount' => '500.00',
            'date' => '2020-01-01 12:00',
        ]);

        $response = $this->putJson('/api/transactions/1', [
            'label' => 'New Label',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $responseData = $response->json()['data'];
        $response->assertStatus(200);
        $this->assertEquals('New Label', $responseData['label']);
    }
}
