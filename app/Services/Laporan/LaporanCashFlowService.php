<?php

namespace App\Services\Laporan;

use App\Models\Akun;
use App\Models\Journal;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

class LaporanCashFlowService
{
    public function index(HttpRequest $request): Collection
    {
        $perusahaan_id = $request->perusahaan_id;
        $start = Carbon::createFromFormat('d/m/Y', $request->start);
        $end = Carbon::createFromFormat('d/m/Y', $request->end);

        $akunKas = Akun::where("is_kas", true)->get();
        $akunKasIds = $akunKas->pluck("id");

        $journalInstance = new Journal();
        $journal = $journalInstance->getJournalAkunsTransactionedWith($akunKasIds->toArray(), $start, $end, $perusahaan_id);
        $saldoAkuns =$journalInstance->getSaldoFromAccounts($akunKasIds->toArray(), $start , $perusahaan_id);
        $saldoAwal = collect($saldoAkuns)->sum();
        $journal = $journal->map(function ($journalItem, $key) use ($akunKas) {
            $journalMapped = collect($journalItem)->groupBy("posisi_akun")->toArray();
            $debitItem = array_key_exists("DEBIT", $journalMapped) ? $journalMapped['DEBIT'] : [];
            $kreditItem = array_key_exists("CREDIT", $journalMapped) ? $journalMapped['CREDIT'] : [];
            return ["akun" => $key,
                "credit" => [
//                    "items" => $debitItem,
                    "items_size" => count($debitItem),
                    "saldo" => collect($debitItem)->sum("jumlah")
                ],
                "debit" => [
//                    "items" => $kreditItem,
                    "items_size" => count($kreditItem),
                    "saldo" => collect($kreditItem)->sum("jumlah")
                ],
            ];
        });
        $journal = collect($journal);
        $report = [
            "journal" => $journal,
            "total_penerimaan" => $journal->sum(function ($n) {
                return $n["debit"]['saldo'];
            }),
            "total_pengeluaran" => $journal->sum(function ($n) {
                return $n["credit"]['saldo'];
            }),
            "saldo_kas_awal" => $saldoAwal,
        ];

        $report["saldo_kas_tersedia"] = (int)$saldoAwal + (int)$report['total_penerimaan'];
        $report["saldo_kas_akhir"] = (int)$report["saldo_kas_tersedia"] - (int)$report['total_pengeluaran'];

        return collect($report);
    }
}
