<?php

namespace App\Services\Laporan;

use App\Models\Akun;
use App\Models\Journal;
use App\Services\Akun\AkunService;
use App\Services\Odoo\Const\AccountType;
use App\Services\Odoo\OdooAccountService;
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
        $odooAccountService = new OdooAccountService();


        $perusahaan_id = $request->perusahaan_id;
        $coa = $request->coa;
        $group = $request->group;
        $start = Carbon::createFromFormat('d/m/Y', $request->start)->addDays(-1);
        $end = Carbon::createFromFormat('d/m/Y', $request->end);

        $akun = Akun::where("kode_akun", $coa)->first();
        

        $saldoAwal = $akun->getSaldoAt( null, $start ,  $perusahaan_id);
        // $saldoAkhir = $akun->getSaldoAt( null, $end ,  $perusahaan_id);

        $groupData = [];
        $journalLawan = (new Journal())->getJournalLawan($akun)->toArray();
        
        $groupData = $journalLawan;
        // $groupData =  $data['records'];
        // $moveNames = collect($groupData)->pluck('move_name')->toArray();


        // $journal = $odooJournalService->getJournalWithMoveName($moveNames,  $start, $end)["records"];
        // if ($journal) {

        //     $data =  collect($journal)->pluck("invoice_line_ids");

        //     $data =  array_merge(...$data);
        //     $journalDetail = $odooApiService->getJournalItemWithIds($data);

        //     // remove __domain from  groupData
        //     $groupData = collect($groupData)->map(function ($item) use ($journal, $journalDetail,  $coa,  $saldoAwal) {
        //         $selectedItem =  collect($journal)->where('name', $item['move_name'])->first();
        //         // dd($selectedItem);
        //         $journalItem = collect($journalDetail)->whereIn('id', $selectedItem['invoice_line_ids'])->toArray();
        //         unset($item['__domain']);
        //         $item["journal_lawan"] = collect($journalItem)->filter(function ($value, $key) use ($coa) {
        //             return !str_contains($value['akun_label'], $coa);
        //         })->toArray();
        //         return $item;
        //     })->toArray();
        // } else {
        //     $groupData = [];
        // }

        $saldoAkhir = $saldoAwal;

        foreach ($groupData as  $keyJournal => $valueJournal) {
                # code...

                $editor = $valueJournal['posisi_akun']== "DEBIT" ? $valueJournal['jumlah'] : - $valueJournal['jumlah'];
                $saldoAkhir = $saldoAkhir +  $editor;
                $groupData[$keyJournal]["saldo_di_line"] = $saldoAkhir;
            
        }


        return collect([
            "akun" => $akun,
            "report" =>  $groupData,
            "saldoAwal" =>  $saldoAwal,
            "saldoAkhir" =>  $saldoAkhir,
        ]);
    }


    // public function  index(HttpRequest $request): Collection
    // {
    //     $odooApiService = new OdooApiService();
    //     $odooJournalService = new OdooJournalService();
    //     $odooAccountService = new OdooAccountService();


    //     $perusahaan_id = $request->perusahaan_id;
    //     $coa = $request->coa;
    //     $group = $request->group;
    //     $start = Carbon::createFromFormat('d/m/Y', $request->start)->addDays(-1)->format('Y-m-d');
    //     $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');


    //     $akun = $odooAccountService->getAkunList([
    //         ["code", "=", $coa]
    //     ])["records"][0];
    //     $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, [
    //         [
    //             "account_id",
    //             "in",
    //             $coa,
    //         ]
    //     ]);

    //     $saldoAwal = $this->getBalanceAt($start, $perusahaan_id, $coa);

    //     $groupData =  $data['records'];
    //     $moveNames = collect($groupData)->pluck('move_name')->toArray();


    //     $journal = $odooJournalService->getJournalWithMoveName($moveNames,  $start, $end)["records"];
    //     if ($journal) {

    //         $data =  collect($journal)->pluck("invoice_line_ids");

    //         $data =  array_merge(...$data);
    //         $journalDetail = $odooApiService->getJournalItemWithIds($data);

    //         // remove __domain from  groupData
    //         $groupData = collect($groupData)->map(function ($item) use ($journal, $journalDetail,  $coa,  $saldoAwal) {
    //             $selectedItem =  collect($journal)->where('name', $item['move_name'])->first();
    //             // dd($selectedItem);
    //             $journalItem = collect($journalDetail)->whereIn('id', $selectedItem['invoice_line_ids'])->toArray();
    //             unset($item['__domain']);
    //             $item["journal_lawan"] = collect($journalItem)->filter(function ($value, $key) use ($coa) {
    //                 return !str_contains($value['akun_label'], $coa);
    //             })->toArray();
    //             return $item;
    //         })->toArray();
    //     } else {
    //         $groupData = [];
    //     }

    //     $saldoAkhir = $saldoAwal;

    //     foreach ($groupData as $key => $value) {
    //         foreach ($value["journal_lawan"] as $keyJournal => $valueJournal) {
    //             # code...
    //             $jorunalData = $value["journal_lawan"][$keyJournal];

    //             $editor = $jorunalData['posisi_akun']== "DEBIT" ? $valueJournal['jumlah'] : - $valueJournal['jumlah'];
    //             $saldoAkhir = $saldoAkhir +  $editor;
    //             $groupData[$key]["journal_lawan"][$keyJournal]["saldo_di_line"] = $saldoAkhir;
    //         }
    //     }


    //     return collect([
    //         "akun" => $akun,
    //         "report" =>  $groupData,
    //         "saldoAwal" =>  $saldoAwal,
    //         "saldoAkhir" =>  $saldoAkhir,
    //     ]);
    // }


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
