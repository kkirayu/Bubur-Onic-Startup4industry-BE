<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Supplier;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Database\Factories\SupplierFactory;
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
        $supplier = SupplierFactory::new()->create();
        $product1= ProductFactory::new()->create();
        $product2= ProductFactory::new()->create();
        $payload = [
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet",
            'bill_details' => [
                [
                    'product_id' => $product1->id,
                    'qty' => 2,
                    'account_id' => 1,
                    'price' => 250000,
                    'total' => 500000,
                    'discount' => 0,
                    'tax' => 0,
                    'subtotal' => 500000,
                ],
                [
                    'product_id' => $product2->id,
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


        $response->assertStatus(200);
    }

    public function testEditBill(): void
    {

        $product1= ProductFactory::new()->create();
        $product2= ProductFactory::new()->create();
        $supplier = SupplierFactory::new()->create();
        $payload = [
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'desc' => "Updated Payload",
            'bill_details' => [
                [
                    'product_id' => $product1->id,
                    'qty' => 2,
                    'account_id' => 1,
                    'price' => 250000,
                    'total' => 500000,
                    'discount' => 0,
                    'tax' => 0,
                    'subtotal' => 500000,
                ],
                [
                    'product_id' => $product2->id,
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
            'supplier_id' => $supplier->id,
            'bill_number' => "BILL-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->putJson('api/keuangan/bill/' . $bill->id, $payload);


        $response->assertStatus(200);
    }


    public function testGetBillBySupplierIds()
    {

        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $supplier = Supplier::first();
        $response = $this->getJson(
            'api/keuangan/bill?filter[supplier_id]=' . $supplier->id . "&filter[payment_status][]=UNPAID&filter[payment_status][]=PARTIALPAID"
        );


        $response->assertStatus(200);
    }


    public function testListBill()
    {

        $supplier = SupplierFactory::new()->create();
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $supplier = Supplier::first();
        $response = $this->getJson(
            'api/keuangan/bill?limit=2'
        );


        $response->assertStatus(200);
    }

    public function testPayMultiBillLunas()
    {

        $supplier = SupplierFactory::new()->create();

        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'total' => 10000,
            'bill_number' => "BILL-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $bill2 = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'total' => 10000,
            'supplier_id' => $supplier->id,
            'bill_number' => "BILL-" . date('YmdHis'),
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

        $response->assertStatus(200);


    }
    public function testPayMultiBillBelumLunas()
    {

        $supplier = SupplierFactory::new()->create();

        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'total' => 10000,
            'bill_number' => "BILL-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $bill2 = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'total' => 10000,
            'supplier_id' => $supplier->id,
            'bill_number' => "BILL-" . date('YmdHis'),
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

        $response->assertStatus(200);


    }
    public function testPayMultiBillBelumLunasDanLunasi()
    {

        $supplier = SupplierFactory::new()->create();

        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'total' => 10000,
            'bill_number' => "BILL-" . date('YmdHis'),
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

        $response->assertStatus(200);


    }


    public function testPostBill(): void
    {
        $supplier = SupplierFactory::new()->create();


        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'bill_number' => "BILL-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->putJson('api/keuangan/bill/' . $bill->id . "/post");

        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'post_status' => 'POSTED',
        ]);

        $response->assertStatus(200);
    }
    public function testUnPostBill(): void
    {

        $supplier = SupplierFactory::new()->create();


        $bill = Bill::create([
            'bill_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'supplier_id' => $supplier->id,
            'post_status' => 'POSTED',
            'bill_number' => "BILL-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->putJson('api/keuangan/bill/' . $bill->id . "/un-post");


        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'post_status' => 'DRAFT',
        ]);
        $response->assertStatus(200);
    }

}
