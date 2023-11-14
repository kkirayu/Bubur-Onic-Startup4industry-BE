<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BillTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $payload = [
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => Supplier::first()->id,
            'total' => 1000000,
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet",
            'bill_details' => [
                [
                    'product_id' => Product::first()->id,
                    'qty' => 2,
                    'account_id' => 1,
                    'price' => 250000,
                    'total' => 500000,
                    'discount' => 0,
                    'tax' => 0,
                    'subtotal' => 500000,
                ],
                [
                    'product_id' =>  Product::first()->id,
                    'qty' => 1,
                    'account_id' => 1,
                    'price' => 500000,
                    'total' => 500000,
                    'discount' => 0,
                    'tax' => 0,
                    'subtotal' => 500000,
                ],
            ],
        ];
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/bill/create-bill',  $payload);

        dd($response->getContent());

        $response->assertStatus(200);
    }
}