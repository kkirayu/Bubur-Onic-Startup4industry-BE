<?php

namespace App\Services\Laporan;

use App\Services\Odoo\OdooApiService;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanNeracaService
{
    public function  index(HttpRequest $request): Collection
    {
        $section = [
            [
                "key" =>  "equity",
                "value" => "Equity"
            ],
            [
                "key" =>  "asset",
                "value" => "Asset"
            ],
            [
                "key" =>  "liability",
                "value" => "Liability"
            ],
        ];

        $perusahaan_id = 1;
        $date = Carbon::createFromFormat('d/m/Y', $request->date);
        $start = $date->startOfMonth()->format('Y-m-d');
        $end = $date->endOfMonth()->format('Y-m-d');

        $data = collect($section)->map(function ($akunGroup) use ($start, $end, $perusahaan_id) {

            $odooApiService = new OdooApiService();
            $groupKey = $akunGroup['key'];

            $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, [$groupKey], 'account_root_id');
            $groupRootData =  $data['groups'];
            // remove __domain from  groupData
            $groupRootData = collect($groupRootData)->map(function ($item) use($odooApiService,$start, $end, $perusahaan_id, $groupKey ) {
                unset($item['__domain']);

                $data = $odooApiService->getBukuBesarWithRootKey($start, $end, $perusahaan_id, [$groupKey], [$item['account_root_id'][0]]);
                $data = collect($data['groups'])->map(function ($item) {
                    unset($item['__domain']);
                    return $item;
                });
                $item["items"] =  $data;
                return $item;
            });

            $groupData =  $data['groups'];

            $groupData = collect($groupData)->map(function ($item) {
                unset($item['__domain']);
                return $item;
            });
            $akunGroup['group'] = $groupRootData;


            return  $akunGroup;
        });
        return $data;
    }
}
