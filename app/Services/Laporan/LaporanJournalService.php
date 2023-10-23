<?php

namespace App\Services\Laporan;

use App\Models\Journal;
use App\Services\Odoo\OdooApiService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LaporanJournalService
{
    public function  index(HttpRequest $request): Collection
    {


        $data = new OdooApiService();

        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');

        $akunDataRaw = $data->getJournalList([
            ['date', '>=', "$start",],
            ['date', '<=', "$end",],
            ['state', '=', 'posted'],
        ]);

        $akunData =  collect($akunDataRaw['records']);
        $invoice_line_ids = (array_merge(...$akunData->pluck("invoice_line_ids")->toArray()));


        $journalData = $data->getJournalItemWithIds($invoice_line_ids);

        $journalData = collect($journalData)->map(function ($item,  $key) {
            $item = (object) $item;
            return [
                "id" => $item->id,
                "perusahaan_id" => 1,
                "cabang_id" => 1,
                "akun" => $item->account_id[0],
                "akun_label" => $item->account_id[1],
                "posisi_akun" => $item->credit > 0 ? "CREDIT" : "DEBIT",
                "deskripsi" => "asdf",
                "jumlah" => $item->credit ? $item->balance * -1 : $item->balance,
                "created_at" => $item->date,
                "updated_at" => "2023-10-06T06:08:34.000000Z",
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
            ];
        });

        $journalData = collect($journalData);


        $data = $akunData->map(function ($item,  $key) use ($journalData) {

            $data = (object) $item;
            $formatted = [
                "id" => $data->id,
                "kode_jurnal" => $data->name,
                "perusahaan_id" => 1,
                "cabang_id" => 1,
                "total_debit" => $data->amount_total ,
                "total_kredit" => $data->amount_total ,
                "deskripsi" =>  $data->ref,
                "posted_at" => $data->state == 'posted' ? $data->__last_update : null,
                "created_at" =>$data->date,
                "updated_at" => "2023-10-06T06:36:51.000000Z",
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
                "judul" => $data->ref,
                "tanggal_transaksi" => $data->date,
                "deleted_at" => null,

                "journal_akuns" => $journalData->whereIn("id", $data->invoice_line_ids)->toArray(),

            ];


            return $formatted;
        });

        return  collect($data);
    }
}
