<?php

namespace App\Services\Keuangan;

use App\Http\Requests\CreateInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\CrudService;

class InvoiceService extends CrudService
{
    public function createInvoice (CreateInvoiceRequest $createInvoiceRequest){

        $invoicePayload = $createInvoiceRequest->only(
            ["invoice_date" ,"due_date", 
            "customer_id", "total", "desc"]
        );
        $invoicePayload['invoice_number'] = "INV-".date('YmdHis');
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

        return  collect($createInvoiceRequest);

    }

}
