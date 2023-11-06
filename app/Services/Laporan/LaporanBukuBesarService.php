<?php

namespace App\Services\Laporan;

use App\Services\Odoo\Const\AccountType;
use App\Services\Odoo\OdooApiService;
use App\Services\Odoo\OdooJournalService;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanBukuBesarService
{
    public function  index(HttpRequest $request): Collection
    {
        $odooApiService = new OdooApiService();
        $odooJournalService = new OdooJournalService();


        $perusahaan_id = $request->perusahaan_id;
        $coa = $request->coa;
        $group = $request->group;
        $start = Carbon::createFromFormat('d/m/Y', $request->start)->addDays(-1)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');
        $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, $coa, "move_name");


        $groupData =  $data['records'];
        $moveNames = collect($groupData)->pluck('move_name')->toArray();


        $journal = $odooJournalService->getJournalWithMoveName($moveNames)["records"];
        $data =  collect($journal)->pluck("invoice_line_ids");

        $data =  array_merge(...$data);
        $journalDetail = $odooApiService->getJournalItemWithIds($data);

        // remove __domain from  groupData
        $groupData = collect($groupData)->map(function ($item) use ($journal, $journalDetail,  $coa) {
            $selectedItem =  collect($journal)->where('name', $item['move_name'])->first();
            // dd($selectedItem);
            $journalItem = collect($journalDetail)->whereIn('id', $selectedItem['invoice_line_ids'])->toArray();
            unset($item['__domain']);
            $item["journal_lawan"] = collect($journalItem)->filter(function ($value, $key) use ($coa) {
                return !str_contains($value['akun_label'], $coa);
            })->toArray();
            return $item;
        });




        return collect([
            "report" =>  $groupData,
            "saldoAwal" =>  $this->getBalanceAt($start, $perusahaan_id, $coa),
            "saldoAkhir" =>  $this->getBalanceAt($end, $perusahaan_id, $coa),
        ]);
    }

    function getBalanceAt($start, $perusahaan_id, $coa)
    {

        $odooApiService = new OdooApiService();

        $data = $odooApiService->getBukuBesarAkun(null, $start, $perusahaan_id, $coa);
        if ($data['length'] >  0) {
            return  collect($data['groups'])->map(function ($item) {
                unset($item['__domain']);
                return $item;
            })[0]['balance'];
        }
        return  0;
    }
}
