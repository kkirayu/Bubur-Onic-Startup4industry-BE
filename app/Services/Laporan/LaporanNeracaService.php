<?php

namespace App\Services\Laporan;

use App\Models\KategoriAkun;
use App\Services\Odoo\OdooAccountService;
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
                "key" => ["asset"],
                "value" => "Kekayaan"
            ],
            [
                "key" =>  ["equity",  'liability'],
                "value" => "Kewajiban"
            ],
        ];
        $perusahaan_id = $request->company;

        $kategori_akun = KategoriAkun::where("perusahaan_id", $perusahaan_id)->get();


        $odooApiService = new OdooAccountService();
        $code = $odooApiService->getAkunList();



        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');

        $data = collect($section)->map(function ($akunGroup) use ($start, $end, $perusahaan_id,  $odooApiService,  $kategori_akun) {

            $groupKey = $akunGroup['key'];

            $data = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, $groupKey,  'account_root_id');

            $groupRootData =  $data['groups'];
            // remove __domain from  groupData
            $groupRootData = collect($groupRootData)->map(function ($item) use ($odooApiService, $start, $end, $perusahaan_id, $groupKey, $kategori_akun) {
                unset($item['__domain']);
                $mapping = $kategori_akun->where("prefix_akun",  substr($item['account_root_id'][1], 0, 1))->first();
                $item["account_root_id"][1] = $mapping  ?  $mapping->nama : $item['account_root_id'][1];

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
