<?php

namespace App\Services\Laporan;

use App\Models\Akun;
use App\Models\Journal;
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
        })->map(function ($item) {
            $item->nama = $item->nama . " (" . $item->prefix_akun . ")";
            return $item;
        });
        $section = [
            [
                "value" => "Pendapatan",
                "isInverted" => false,
                "key" => $kategori_akun->filter(function ($item) {
                    return str_starts_with($item->prefix_akun, "4");
                })->pluck("nama", "id")->toArray()
            ],
            [
                "key" => $kategori_akun->filter(function ($item) {
                    return str_starts_with($item->prefix_akun, "5") ||  str_starts_with($item->prefix_akun, "6");
                })->pluck("nama", "id")->toArray(),
                "isInverted" => true,
                "value" => "Pengeluaran"
            ],
            [
                "key" => $kategori_akun->filter(function ($item) {
                    return str_starts_with($item->prefix_akun, "7");
                })->pluck("nama", "id")->toArray(),
                "isInverted" => true,
                "value" => "Pajak"
            ],
        ];


        $perusahaan_id = $request->company;


        $odooApiService = new OdooAccountService();



        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');




        $journal = new Journal();

        $akun = Akun::where("perusahaan_id", $perusahaan_id)->get();
        $kategoriAkun = KategoriAkun::where("perusahaan_id", $perusahaan_id)->get();
        $accountSaldo = $journal->getSaldo($end, $perusahaan_id);
        $saldoAwal = $journal->getSaldo($start, $perusahaan_id);

        $akun =  $akun->map(function ($item) use ($accountSaldo, $saldoAwal) {
            $item->saldo = 0;
            $item->saldo_awal = 0;
            if ($accountSaldo->has($item->kode_akun)) {
                $item->saldo = $accountSaldo[$item->kode_akun];
            }
            if ($saldoAwal->has($item->kode_akun)) {
                $item->saldo_awal = $saldoAwal[$item->kode_akun];
            }
            return $item->toArray();
        });
        $kategoriAkun = $kategoriAkun->map(function ($item) use ($akun) {
            $item->akun = $akun->where("kategori_akun_id", $item->id)->toArray();
            $item->total_awal = collect($item->akun)->sum("saldo_awal");
            $item->total_akhir = collect($item->akun)->sum("saldo");
            return $item->toArray();
        });

        $section = collect($section)->map(function ($item,  $key) use ($kategoriAkun) {
            $data = collect($item["key"])->map(function ($item, $key) use ($kategoriAkun) {
                $kategoriAkunItem = $kategoriAkun->where("id", $key)->first();
                return $kategoriAkunItem;
            });
            return [
                "key" => $item["value"],
                "value" => $data->toArray(),
                "total" => $data->sum("total_akhir"),
                "isInverted" => $item["isInverted"]
            ];
        });

        return $section;
    }
}
