<?php

namespace App\Services\Keuangan;

use App\Http\Requests\CreateBillRequest;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\CrudService;

class BillService extends CrudService
{

    public function createbill (CreateBillRequest $createBillRequest){

        $billPayload = $createBillRequest->only(
            ["bill_date" ,"due_date", 
            "supplier_id", "total", "desc"]
        );
        $billPayload['bill_number'] = "INV-".date('YmdHis');
        $billPayload['perusahaan_id'] = request()->perusahaan_id;
        $billPayload['cabang_id'] = request()->cabang_id;
        $billPayload['payment_status'] = "UNPAID";
        $bill = Bill::create($billPayload);
        
        $billItems = $createBillRequest->bill_details;
        $billItems = collect($billItems)->map(function ($item) {
            $item['perusahaan_id'] = request()->perusahaan_id;
            $item['cabang_id'] = request()->cabang_id;
            return $item;
        })->toArray();
        $bill->billDetails()->createMany($billItems);

        return  collect($createBillRequest);

    }
}
