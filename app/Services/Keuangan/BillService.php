<?php

namespace App\Services\Keuangan;

use App\Http\Requests\CreateBillRequest;
use App\Http\Requests\PayBillRequest;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\CrudService;

class BillService extends CrudService
{

    public function createbill(CreateBillRequest $createBillRequest)
    {


        $billItems = $createBillRequest->bill_details;
        $total = collect($billItems)->sum("subtotal");
        $billPayload = $createBillRequest->only(
            ["bill_date", "due_date",
                "supplier_id",  "desc"]
        );
        $billPayload['bill_number'] = "BILL-" . date('YmdHis');
        $billPayload['perusahaan_id'] = request()->perusahaan_id;
        $billPayload['total'] = $total;
        $billPayload['cabang_id'] = request()->cabang_id;

        $billPayload['payment_status'] = "UNPAID";
        $bill = Bill::create($billPayload);

        $billItems = collect($billItems)->map(function ($item) {
            $item['perusahaan_id'] = request()->perusahaan_id;
            $item['cabang_id'] = request()->cabang_id;
            return $item;
        })->toArray();
        $bill->billDetails()->createMany($billItems);

        return collect($createBillRequest);

    }

    public function update(mixed $id, FormRequest|\Laravolt\Crud\Contracts\UpdateRequestContract $createBillRequest): \Laravolt\Crud\CrudModel
    {
        $bill = Bill::where("id", $createBillRequest->id)->first();

        $billItems = $createBillRequest->bill_details;
        $total = collect($billItems)->sum("subtotal");

        $billPayload = $createBillRequest->only(
            ["bill_date", "due_date",
                "supplier_id", "desc"]
        );
        $billPayload['perusahaan_id'] = request()->perusahaan_id;
        $billPayload['cabang_id'] = request()->cabang_id;
        $billPayload['total'] = $total;
        $billPayload['payment_status'] = "UNPAID";
        $bill->update($billPayload);

        BillItem::where("perusahaan_id", request()->perusahaan_id)
            ->where("cabang_id", request()->cabang_id)
            ->where("bill_id", $bill->id)->delete();


        $billItems = collect($billItems)->map(function ($item) {
            $item['perusahaan_id'] = request()->perusahaan_id;
            $item['cabang_id'] = request()->cabang_id;
            return $item;
        })->toArray();
        $bill->billDetails()->createMany($billItems);
        $bill->load(["billDetails"]);
        return $bill;

    }


    public function payBill(PayBillRequest $payBillRequest)
    {

        $billIds = collect($payBillRequest->bills)->pluck("bill_id");
        $bill = Bill::whereIn("id", $billIds)->get();

        $bill = $bill->map(function ($bill) use ($payBillRequest) {
            $payIn = collect($payBillRequest->bills)->where("bill_id", $bill->id)->first();
            $rest_amount = $bill->paid_total ?  $bill->total -$bill->paid_total  - $payIn['amount_paid'] : $payIn['amount_paid'];


            if ($rest_amount == 0) {

                $bill->paid_total = $bill->total;
                $bill->payment_status = "PAID";
            } else if ($rest_amount > 0) {
                $bill->paid_total = $bill->paid_total ?  $bill->total -$bill->paid_total  - $payIn['amount_paid'] : $payIn['amount_paid'];
                $bill->payment_status = "PARTIALPAID";
            } else  {
                $bill->error = true;
            }
            return $bill;
        });
        if ($bill->where("error", true)->count() > 0) {
            throw ValidationException::withMessages(['amount_paid' => 'Ada Kesalahan pada jumlah bayar']);
        }
        $bill->each(function ($bill) {
            $bill->update();
        });
        return $bill;

    }


    public function postBill()
    {
        $bill = Bill::where("id", request()->bill_id)->first();

        $bill->post_status = "POSTED";
        $bill->update();
        return $bill;
    }
    public function unPostBill()
    {
        $bill = Bill::where("id", request()->bill_id)->first();

        $bill->post_status = "DRAFT";
        $bill->update();
        return $bill;
    }

}
