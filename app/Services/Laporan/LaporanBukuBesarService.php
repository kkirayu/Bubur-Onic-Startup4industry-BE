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
        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');

        $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, $coa , "move_name");
    

        $groupData =  $data['records'];
        $moveNames = collect($groupData)->pluck('move_name')->toArray();

        
        $journal = $odooJournalService->getJournalWithMoveName($moveNames)["records"];
        $data=  collect($journal)->pluck("invoice_line_ids");

        $data =  array_merge(...$data);
        $journalDetail = $odooApiService->getJournalItemWithIds($data);
        
        // remove __domain from  groupData
        $groupData = collect($groupData)->map(function ($item) use ($journal,$journalDetail,  $coa) {
            $selectedItem =  collect($journal)->where('name', $item['move_name'])->first();
            // dd($selectedItem);
            $journalItem = collect($journalDetail)->whereIn('id', $selectedItem['invoice_line_ids'])->toArray();
            unset($item['__domain']);
            $item["journal_lawan"] = collect( $journalItem)->filter(function ($value, $key) use ($coa) {
                return !str_contains($value['akun_label'], $coa);
            })->toArray();
            return $item;
        });



        dd($groupData);
        // // dd($groupDetail[0]);
        // // // Search groupDetail By move_name
        // // $data = collect($groupData)->map(function ($item) use ($groupDetail) {
        // //     $item['items'] =  array_values($groupDetail->where('account_id.0', $item['account_id'][0])->toArray());
        // //     return $item;
        // // });

        // return $groupData;
    }
}
