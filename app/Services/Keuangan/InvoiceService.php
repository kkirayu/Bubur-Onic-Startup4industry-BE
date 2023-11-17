<?php

namespace App\Services\Keuangan;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\PayInvoiceRequest;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Laravolt\Crud\CrudService;

class InvoiceService extends CrudService
{
    public function createInvoice(CreateInvoiceRequest $createInvoiceRequest)
    {

        $invoicePayload = $createInvoiceRequest->only(
            ["invoice_date", "due_date",
                "customer_id", "total", "desc"]
        );
        $invoicePayload['invoice_number'] = "INV-" . date('YmdHis');
        $invoicePayload['perusahaan_id'] = request()->perusahaan_id;
        $invoicePayload['cabang_id'] = request()->cabang_id;
        $invoicePayload['payment_status'] = "UNPAID";
        $invoice = Invoice::create($invoicePayload);

        $invoiceItems = $createInvoiceRequest->invoice_details;
        $invoiceItems = collect($invoiceItems)->map(function ($item) {
            $item['perusahaan_id'] = request()->perusahaan_id;
            $item['cabang_id'] = request()->cabang_id;
            return $item;
        })->toArray();
        $invoice->invoiceDetails()->createMany($invoiceItems);

        return collect($createInvoiceRequest);

    }

    public function update(mixed $id, FormRequest|\Laravolt\Crud\Contracts\UpdateRequestContract $createBillRequest): \Laravolt\Crud\CrudModel
    {
        $invoice = Invoice::where("id", $createBillRequest->id)->first();

        $invoiceItems = $createBillRequest->invoice_details;
        $total = collect($invoiceItems)->sum("subtotal");

        $invoicePayload = $createBillRequest->only(
            ["invoice_date", "due_date",
                "supplier_id", "desc"]
        );
        $invoicePayload['perusahaan_id'] = request()->perusahaan_id;
        $invoicePayload['cabang_id'] = request()->cabang_id;
        $invoicePayload['total'] = $total;
        $invoicePayload['payment_status'] = "UNPAID";
        $invoice->update($invoicePayload);

        InvoiceItem::where("perusahaan_id", request()->perusahaan_id)
            ->where("cabang_id", request()->cabang_id)
            ->where("invoice_id", $invoice->id)->delete();


        $invoiceItems = collect($invoiceItems)->map(function ($item) {
            $item['perusahaan_id'] = request()->perusahaan_id;
            $item['cabang_id'] = request()->cabang_id;
            return $item;
        })->toArray();
        $invoice->invoiceDetails()->createMany($invoiceItems);
        $invoice->load(["invoiceDetails"]);
        return $invoice;

    }

    public function bayarInvoice(PayInvoiceRequest $payInvoiceRequest)
    {

        $invoiceIds = collect($payInvoiceRequest->invoices)->pluck("invoice_id");
        $invoices = Invoice::whereIn("id", $invoiceIds)->get();

        $invoices = $invoices->map(function ($invoice) use ($payInvoiceRequest) {
            $payIn = collect($payInvoiceRequest->invoices)->where("invoice_id", $invoice->id)->first();
            $rest_amount = $invoice->paid_total ?  $invoice->total -$invoice->paid_total  - $payIn['amount_paid'] : $payIn['amount_paid'];


            if ($rest_amount == 0) {

                $invoice->paid_total = $invoice->total;
                $invoice->payment_status = "PAID";
            } else if ($rest_amount > 0) {
                $invoice->paid_total = $invoice->paid_total ?  $invoice->total -$invoice->paid_total  - $payIn['amount_paid'] : $payIn['amount_paid'];
                $invoice->payment_status = "PARTIALPAID";
            } else  {
                $invoice->error = true;
            }
            return $invoice;
        });
        if ($invoices->where("error", true)->count() > 0) {
            throw ValidationException::withMessages(['amount_paid' => 'Ada Kesalahan pada jumlah bayar']);
        }
        $invoices->each(function ($invoice) {
            $invoice->update();
        });
        return $invoices;

    }

}
