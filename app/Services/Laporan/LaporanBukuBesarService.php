<?php

namespace App\Services\Laporan;

use App\Services\Odoo\Const\AccountType;
use App\Services\Odoo\OdooApiService;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanBukuBesarService
{
    public function  index(HttpRequest $request): Collection
    {
        $odooApiService = new OdooApiService();

        $perusahaan_id = $request->perusahaan_id;
        $group = $request->group;
        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');   

        $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, [$group]);
        $groupData =  $data['groups'];
        // remove __domain from  groupData
        $groupData = collect($groupData)->map(function ($item) {
            unset($item['__domain']);
            return $item;
        });
        $moveNameData = collect($groupData)->pluck("account_id.0");
        
        $groupDetail = $odooApiService->getBukuBesarDetail($moveNameData->toArray(), $perusahaan_id , $start,  $end);
        $groupDetail = collect($groupDetail['records']);
        // dd($groupDetail[0]);
        // Search groupDetail By move_name
        $data = collect($groupData)->map(function ($item) use ($groupDetail) {
            $item['items'] =  array_values($groupDetail->where('account_id.0', $item['account_id'][0])->toArray());
            return $item;
        });

        return $data;
    }
}
