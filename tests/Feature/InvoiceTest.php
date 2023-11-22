<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Database\Factories\CustomerFactory;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{

    use WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $faker = $this->faker;


        $product1 = $this->createProduct();
        $product2 = $this->createProduct();
        $customer = $this->createCustomer();
        $payload = [
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => $customer->id,
            'total' => 1000000,
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet",
            'invoice_details' => [
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
        $response = $this->postJson('api/keuangan/invoice/create-invoice', $payload);


        $response->assertStatus(200);
    }


    public function testEditBill(): void
    {
        $faker = $this->faker;
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();


        $customer = $this->createCustomer();
        $payload = [
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => $customer->id,
            'desc' => "Updated Payload",
            'invoice_details' => [
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

        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => $customer->id,
            'invoice_number' => "INV-" . date('YmdHis'),
            'desc' => "lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor",
            "perusahaan_id" => 1,
            "cabang_id" => 1,
        ]);
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $response = $this->putJson('api/keuangan/invoice/' . $invoice->id, $payload);


        $response->assertStatus(200);
    }

    public function testGetInvoiceByCustomerIds()
    {
        $faker = $this->faker;
        $customer = $this->createCustomer();
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $customer = Customer::first();
        $response = $this->getJson(
            'api/keuangan/invoice?filter[customer_id]=' . $customer->id . "&filter[payment_status][]=UNPAID&filter[payment_status][]=PARTIALPAID"
        );


        $response->assertStatus(200);
    }
    public function testGetSemuaInvoice()
    {
        $faker = $this->faker;
        $customer = $this->createCustomer();
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $customer = Customer::first();
        $response = $this->getJson(
            'api/keuangan/invoice?limit=2'
        );


        $response->assertStatus(200);
    }

    public function testPayMultiInvoiceLunas()
    {


        $faker = $this->faker;
        $customer = $this->createCustomer();
        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => $customer->id,
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
            'customer_id' => $customer->id,
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

        $response->assertStatus(200);


    }
    public function testPayMultiInvoiceBelumLunas()
    {

        $faker = $this->faker;
        $customer = $this->createCustomer();

        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => $customer->id,
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
            'customer_id' => $customer->id,
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

        $response->assertStatus(200);


    }
    public function testPayMultiInvoiceBelumLunasDanLunasi()
    {

        $faker = $this->faker;
        $customer = $this->createCustomer();

        $invoice = Invoice::create([
            'invoice_date' => '2022-11-01',
            'due_date' => '2022-11-30',
            'customer_id' => $customer->id,
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

        $response->assertStatus(200);


    }

    /**
     * @param \Faker\Generator $faker
     * @return Customer|\Illuminate\Database\Eloquent\Model
     */
    public function createCustomer(): Customer|\Illuminate\Database\Eloquent\Model
    {
        $faker = $this->faker();

        $customer =  CustomerFactory::new()->create();
        return $customer;
    }

    /**
     * @param \Faker\Generator $faker
     * @return Customer|\Illuminate\Database\Eloquent\Model
     */
    public function createProduct(): Customer|\Illuminate\Database\Eloquent\Model
    {
        $faker = $this->faker();
        $customer = ProductFactory::new()->create();
        return $customer;
    }
}
