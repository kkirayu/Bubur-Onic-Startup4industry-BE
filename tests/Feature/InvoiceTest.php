<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $payload = [
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => Customer::first()->id,
            'total' => 1000000,
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet",
            'invoice_details' => [
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
        $response = $this->postJson('api/keuangan/invoice/create-invoice', $payload);

        dump($response->getContent());

        $response->assertStatus(200);
    }


    public function testEditBill(): void
    {
        $payload = [
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => Customer::first()->id,
            'desc' => "Updated Payload",
            'invoice_details' => [
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

        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => Customer::first()->id,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->putJson('api/keuangan/invoice/' . $invoice->id, $payload);

        dump($response->getContent());

        $response->assertStatus(200);
    }

    public function testGetInvoiceByCustomerIds()
    {

        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $customer = Customer::first();
        $response = $this->getJson(
            'api/keuangan/invoice?filter[customer_id]=' . $customer->id . "&filter[payment_status][]=UNPAID&filter[payment_status][]=PARTIALPAID"
        );

        dump($response->getContent());

        $response->assertStatus(200);
    }

    public function testPayMultiInvoiceLunas()
    {


        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => Customer::first()->id,
            'total' => 10000,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $invoice2 = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'total' => 10000,
            'customer_id' => Customer::first()->id,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);


        $invoices = collect([$invoice, $invoice2]);


        $payload = $invoices->map(function ($item) {
            return [
                "invoice_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => $item->total - $item->paid_total -1000,
            ];
        })->toArray();

        $payload =[
            "invoices" => $payload
        ];
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/invoice/pay-invoice', $payload);

        dump($response->getContent());
        $response->assertStatus(200);


    }
    public function testPayMultiInvoiceBelumLunas()
    {
        $customer = Customer::first();


        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => Customer::first()->id,
            'total' => 10000,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $invoice2 = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'total' => 10000,
            'customer_id' => Customer::first()->id,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $invoices = collect([$invoice, $invoice2]);


        $payload = $invoices->map(function ($item) {
            return [
                "invoice_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => $item->total -1000,
            ];
        })->toArray();

        $payload =[
            "invoices" => $payload
        ];


        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/invoice/pay-invoice', $payload);

        dump($response->getContent());
        $response->assertStatus(200);


    }
    public function testPayMultiInvoiceBelumLunasDanLunasi()
    {
        $customer = Customer::first();


        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => Customer::first()->id,
            'total' => 10000,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);

        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $invoices = collect([$invoice]);


        $payload = $invoices->map(function ($item) {
            return [
                "invoice_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => $item->total -1000,
            ];
        })->toArray();

        $payload =[
            "invoices" => $payload
        ];


        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/invoice/pay-invoice', $payload);

        dump($response->getContent());

        $payload = $invoices->map(function ($item) {
            return [
                "invoice_id" => $item->id,
                "total" => $item->total,
                "paid_total" => $item->paid_total,
                "amount_paid" => 1000,
            ];
        })->toArray();

        $payload =[
            "invoices" => $payload
        ];


        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->postJson('api/keuangan/invoice/pay-invoice', $payload);

        dump($response->getContent());
        $response->assertStatus(200);


    }
}
