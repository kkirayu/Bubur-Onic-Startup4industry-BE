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
        $groupData = collect($groupData)->map(function ($item) use ($odooApiService, $start, $end, $perusahaan_id,) {
            unset($item['__domain']);
            $moveNameData = $item['account_id'][0];
            // dd( $item['account_id']);

            $groupDetail = $odooApiService->getBukuBesarDetail([$moveNameData], $perusahaan_id, $start,  $end);
            $groupDetail = collect($groupDetail['records']);
            $item['items'] = $groupDetail;
            return $item;
        });
        // dd($groupDetail[0]);
        // // Search groupDetail By move_name
        // $data = collect($groupData)->map(function ($item) use ($groupDetail) {
        //     $item['items'] =  array_values($groupDetail->where('account_id.0', $item['account_id'][0])->toArray());
        //     return $item;
        // });

        return $groupData;
    }
}
