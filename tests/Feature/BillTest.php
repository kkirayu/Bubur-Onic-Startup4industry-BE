<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Supplier;
use App\Models\Product;
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
                    'product_id' => Product::first()->id,
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
        $response = $this->postJson('api/keuangan/bill/create-bill', $payload);

        dump($response->getContent());

        $response->assertStatus(200);
    }

    public function testEditBill(): void
    {
        $payload = [
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => Supplier::first()->id,
            'desc' => "Updated Payload",
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
                    'product_id' => Product::first()->id,
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

        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => Supplier::first()->id,
            'bill_number' => "BILL-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->putJson('api/keuangan/bill/' . $bill->id, $payload);

        dump($response->getContent());

        $response->assertStatus(200);
    }


    public function testGetBillBySupplierIds()
    {

        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $customer = Supplier::first();
        $response = $this->getJson(
            'api/keuangan/bill?filter[supplier_id]=' . $customer->id . "&filter[payment_status][]=UNPAID&filter[payment_status][]=PARTIALPAID"
        );

        dump($response->getContent());

        $response->assertStatus(200);
    }

    public function testPayMultiBillLunas()
    {


        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => Supplier::first()->id,
            'total' => 10000,
            'bill_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $bill2 = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'total' => 10000,
            'supplier_id' => Supplier::first()->id,
            'bill_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);


        $bills = collect([$bill, $bill2]);


        $payload = $bills->map(function ($item) {
            return [
                "bill_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => $item->total - $item->paid_total ,
            ];
        })->toArray();

        $payload =[
            "bills" => $payload
        ];
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/bill/pay-bill', $payload);

        dump($response->getContent());
        $response->assertStatus(200);


    }
    public function testPayMultiBillBelumLunas()
    {
        $customer = Supplier::first();


        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => Supplier::first()->id,
            'total' => 10000,
            'bill_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $bill2 = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'total' => 10000,
            'supplier_id' => Supplier::first()->id,
            'bill_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $bills = collect([$bill, $bill2]);


        $payload = $bills->map(function ($item) {
            return [
                "bill_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => $item->total -1000,
            ];
        })->toArray();

        $payload =[
            "bills" => $payload
        ];


        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/bill/pay-bill', $payload);

        dump($response->getContent());
        $response->assertStatus(200);


    }
    public function testPayMultiBillBelumLunasDanLunasi()
    {
        $customer = Supplier::first();


        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => Supplier::first()->id,
            'total' => 10000,
            'bill_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $bills = collect([$bill]);


        $payload = $bills->map(function ($item) {
            return [
                "bill_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => $item->total -1000,
            ];
        })->toArray();

        $payload =[
            "bills" => $payload
        ];


        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/bill/pay-bill', $payload);

        dump($response->getContent());

        $payload = $bills->map(function ($item) {
            return [
                "bill_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => 1000,
            ];
        })->toArray();

        $payload =[
            "bills" => $payload
        ];


        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/bill/pay-bill', $payload);

        dump($response->getContent());
        $response->assertStatus(200);


    }
}
