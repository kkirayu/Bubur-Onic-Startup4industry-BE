<?php

namespace App\Services\Laporan;

use App\Models\Akun;
use App\Models\Journal;
use App\Models\KategoriAkun;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanPerubahanModalService
{
    public function index(HttpRequest $request): Collection
    {


        $journalInstance = new Journal();
        $perusahaan_id = $request->perusahaan_id;
        $start = Carbon::createFromFormat('d/m/Y', $request->start);
        $end = Carbon::createFromFormat('d/m/Y', $request->end);


        $modalSkarang = [300, 310];
        $akunModalAwal = $this->getModal($modalSkarang, $journalInstance, null, $start, $perusahaan_id);

        $storanInvestor = [300, 300];
        $akunSetoranInvestor = $this->getModal($storanInvestor, $journalInstance, $start, $end, $perusahaan_id);
        $penarikanPrive = [320];
        $saldoPenarikanPrive = $this->getModal($penarikanPrive, $journalInstance, $start, $end, $perusahaan_id);
        $akunRlDitahan = [350, 360];
        $saldoRlDitahan = $this->getModal($akunRlDitahan, $journalInstance, $start, $end, $perusahaan_id);
        $permodalanLainnya = [330];
        $saldoPermodalanLainnya = $this->getModal($permodalanLainnya, $journalInstance, $start, $end, $perusahaan_id);
        $akunModal = [
            "akun_modal" => $akunModalAwal,
            "storan_investor" => $akunSetoranInvestor,
            "saldo_penarikan_prive" => $saldoPenarikanPrive,
            "laba_rugi" => $saldoRlDitahan];

        $totalModalBerputar = collect($akunModal)->map(function ($item,  $key) {
            return $key == "saldo_penarikan_prive" ? -1 * $item['total'] : $item['total'];
        })->sum();

        $akunModal["total_modal_berputar"]= $totalModalBerputar;
        $akunModal["permodalan_lainnya"]= $saldoPermodalanLainnya;

        $akunModal["total_modal"]= $totalModalBerputar + $saldoPermodalanLainnya['total'];
        return collect($akunModal);


    }

    /**
     * @param array $modalSkarang
     * @param Journal $journalInstance
     * @param bool|Carbon $start
     * @param mixed $perusahaan_id
     * @return Collection
     */
    public function getModal(array $modalSkarang, Journal $journalInstance, bool|Carbon|null $start, bool|Carbon $end, mixed $perusahaan_id): array
    {
        $kategoriModalSkarang = KategoriAkun::whereIn("prefix_akun", $modalSkarang)->get();
        $akunModalSkarang = Akun::whereIn("kategori_akun_id", $kategoriModalSkarang->pluck("id"))->get();
//        dd($akunModalSkarang->pluck("id", "kode_akun")->toArray());
        $saldoAwal = $journalInstance->getSaldoFromAccountsWithRange($akunModalSkarang->pluck("id")->toArray(), $start, $end, $perusahaan_id);

        $akunModalAwal = $akunModalSkarang->map(function ($item) use ($saldoAwal) {
            $item->saldo = 0;
            if ($saldoAwal->has($item->kode_akun)) {
                $item->saldo = $saldoAwal[$item->kode_akun];
            }
            return $item->toArray();
        });
        return [
            "akun" => $akunModalAwal,
            "total" => collect($akunModalAwal)->sum("saldo")
        ];
    }
}
