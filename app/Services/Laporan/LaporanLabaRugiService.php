<?php

namespace App\Services\Laporan;

use App\Models\KategoriAkun;
use App\Services\Akun\AkunService;
use App\Services\Odoo\OdooAccountService;
use App\Services\Odoo\OdooApiService;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanLabaRugiService
{
    public function  index(HttpRequest $request): Collection
    {

        $kategori_akun = KategoriAkun::where("perusahaan_id", $request->perusahaan_id)->where("parent_kategori_akun", "!=", null)->get();
        $kategori_akun = $kategori_akun->filter(function ($item) {
            return str_starts_with($item->prefix_akun, "4") ||  str_starts_with($item->prefix_akun, "5") || str_starts_with($item->prefix_akun, "6") || str_starts_with($item->prefix_akun, "7") && $item->parent_kategori_akun != null;
        });
        $section = [
            [
                "value" => "Pendapatan",
                "isInverted" => true,
                "key" => $kategori_akun->filter(function ($item) {
                    return str_starts_with($item->prefix_akun, "4");
                })->pluck("nama", "prefix_akun")->toArray()
            ],
            [
                "key" => $kategori_akun->filter(function ($item) {
                    return str_starts_with($item->prefix_akun, "5") ||  str_starts_with($item->prefix_akun, "6");
                })->pluck("nama", "prefix_akun")->toArray(),
                "isInverted" => true,
                "value" => "Pengeluaran"
            ],
            [
                "key" => $kategori_akun->filter(function ($item) {
                    return str_starts_with($item->prefix_akun, "7");
                })->pluck("nama", "prefix_akun")->toArray(),
                "isInverted" => true,
                "value" => "Pajak"
            ],
        ];


        $perusahaan_id = $request->company;


        $odooApiService = new OdooAccountService();



        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');



        // "|"
        // ["account_id", "ilike", "100"]
        // ["account_id", "ilike", "101"]
        $domain = [];
        foreach (array_values($kategori_akun->toArray()) as $key => $item) {
            if ($key != count($kategori_akun) - 1) {

                $domain[] = "|";
            }
            $domain[] = ["account_id", "ilike", $item['prefix_akun']];
        };

        $startDomain  = array_merge([
            "&", [
                "date", "<=", $start
            ],
        ], $domain);
        $startData = $odooApiService->getBukuBesarReportWihtGroup($start, $end, $perusahaan_id, $startDomain,  'account_id');

        $startData = $startData['groups'];
        $startData = collect($startData)->map(function ($dataitem) {

            unset($dataitem['__domain']);
            return  $dataitem;
        });

        $endDomain  = array_merge([
            "&", [
                "date", "<=", $end
            ],
        ], $domain);
        $data = $odooApiService->getBukuBesarReportWihtGroup($start, $end, $perusahaan_id, $endDomain,  'account_id');
        $dataJournal = $odooApiService->getBukuBesarReport($start, $end, $perusahaan_id, $endDomain,  'account_id');
        $data = $data['groups'];
        $data = collect($data)->map(function ($dataitem) {

            unset($dataitem['__domain']);
            return  $dataitem;
        });
        $dataJournal = $dataJournal['records'];
        $dataJournal = collect($dataJournal)->map(function ($dataitem) {

            unset($dataitem['__domain']);
            return  $dataitem;
        });

        $data = collect($section)->map(function ($akunGroup) use ($data,  $dataJournal,  $startData) {


            $keys = $akunGroup['key'];
            foreach ($keys as $key => $value) {

                $account = collect($data)->filter(function ($item) use ($key) {

                    return str_starts_with($item['account_id'][1], $key);
                })->map(function ($account)  use ($dataJournal, $startData, $akunGroup) {

                    $isInverted = $akunGroup['isInverted'] ? -1 : 1;
                    $journals = collect($dataJournal)->filter(function ($item) use ($account) {
                        return str_starts_with($item['account_id'][0], $account['account_id'][0]);;
                    })->toArray();
                    $account ["balance"] = $account['balance'] * $isInverted;
                    $startBalance = $startData->where("account_id", $account['account_id'])->first();
                    $account["balance_start"] = $startBalance  ?  $startBalance['balance'] * $isInverted : 0;
                    $account['journals'] = $journals;
                    return $account;
                })->toArray();
                $akunGroup['key'][$key] = [
                    "value" => $value,
                    "key" => $account,
                    "total_awal" => collect($account)->sum("balance_start"),
                    "total_akhir" => collect($account)->sum("balance"),

                ];
            }

            $akunGroup['total_akhir'] = collect($akunGroup['key'])->sum("total_akhir");
            $akunGroup['total_awal'] = collect($akunGroup['key'])->sum("total_awal");

            return $akunGroup;
        });

        return $data;
    }
}
