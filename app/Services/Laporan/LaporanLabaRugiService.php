<?php

namespace App\Services\Laporan;

use App\Services\Odoo\OdooApiService;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanLabaRugiService
{
    public function  index(HttpRequest $request): Collection
    {
        $section = [
            [
                "key" => ["income"],
                "value" => "Pendapatan"
            ],
            [
                "key" =>  ["expense"],
                "value" => "Pengeluaran"
            ],
        ];

        $rootAccountMapping = collect([
            [
                "key" => 11,
                "value" => "coba"
            ]
        ]);
        $odooApiService = new OdooApiService();
        $code = $odooApiService->getAkunList();


        $perusahaan_id = 1;
        $date = Carbon::createFromFormat('d/m/Y', $request->date);
        $start = $date->startOfMonth()->format('Y-m-d');
        $end = $date->endOfMonth()->format('Y-m-d');

        $data = collect($section)->map(function ($akunGroup) use ($start, $end, $perusahaan_id,  $odooApiService,  $rootAccountMapping) {

            $groupKey = $akunGroup['key'];

            $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, $groupKey,  'account_root_id');
            $groupRootData =  $data['groups'];
            // remove __domain from  groupData
            $groupRootData = collect($groupRootData)->map(function ($item) use ($odooApiService, $start, $end, $perusahaan_id, $groupKey, $rootAccountMapping) {
                unset($item['__domain']);
                $mapping = $rootAccountMapping->where("key",  $item['account_root_id'][1])->first();
                $item["account_root_id"][1] = $mapping  ?  $mapping['value'] : $item['account_root_id'][1];

                $data = $odooApiService->getBukuBesarWithRootKey(null, $start, $perusahaan_id, $groupKey, [$item['account_root_id'][0]]);
                $dataAwal = collect($data['groups'])->map(function ($item) {
                    unset($item['__domain']);
                    return $item;
                });

                $data = $odooApiService->getBukuBesarWithRootKey(null, $end, $perusahaan_id, $groupKey, [$item['account_root_id'][0]]);
                $dataAkhir = collect($data['groups'])->map(function ($item) {
                    unset($item['__domain']);
                    return $item;
                });
                $data = collect($dataAkhir)->map(function ($item) use ($dataAwal, $start,  $end) {
                    $dataAkhir = $item['balance'];
                    $dataAwal =  $dataAwal->where("account_id", $item['account_id'])->first();
                    $dataAwal = $dataAwal ? $dataAwal['balance'] : 0;
                    $item['saldo'] = [
                        "dataAwal" => [
                            "tanggal" => $start,
                            "saldo" => $dataAwal
                        ],
                        "dataAkhir" => [
                            "tanggal" => $end,
                            "saldo" => $dataAkhir
                        ],
                        "selisih" => $dataAkhir - $dataAwal
                    ];
                    return $item;
                });

                $totalAwal = $dataAwal->sum('balance');
                $totalAkhir = $dataAkhir->sum('balance');


                $item["total_awal"] = $totalAwal;


                $item["total_akhir"] = $totalAkhir;

                $item["tanggal_awal"] = $start;


                $item["tanggal_akhir"] = $end;

                $item["items"] = $data;
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
