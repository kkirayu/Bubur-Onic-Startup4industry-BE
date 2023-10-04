<?php

namespace App\Services\Journal;

use App\Http\Requests\CreateJournalRequest;
use App\Models\Journal;
use App\Models\JournalAkun;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Laravolt\Crud\CrudService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class JournalService extends CrudService
{

    public function createJournal(CreateJournalRequest $createJournalRequest)
    {

        // dd($createJournalRequest->all());

        $akun = collect($createJournalRequest->input("akuns"));
        $totalDebit = $akun->pluck("debit")->sum();
        $totalCredit = $akun->pluck("credit")->sum();

        if ($totalDebit != $totalCredit) {

            throw ValidationException::withMessages(['akuns' => 'Total debit dan kredit tidak sama']);
        }

        $journal = Journal::create([
            "perusahaan_id" => $createJournalRequest->perusahaan_id,
            "cabang_id" => $createJournalRequest->cabang_id,
            "kode_jurnal" => "JOURNAL-" . date("YmdHis") . "-" . rand(100, 999),
            "deskripsi" => $createJournalRequest->input("deskripsi"),
            "tanggal_transaksi" => $createJournalRequest->input("tanggal_transaksi"),
            "judul" => $createJournalRequest->input("judul"),
            "total_debit" => $totalDebit,
            "total_kredit" => $totalCredit,
        ]);


        foreach ($akun as $key => $value) {
            JournalAkun::create([
                "perusahaan_id" => $createJournalRequest->perusahaan_id,
                "cabang_id" => $createJournalRequest->cabang_id,
                "journal_id" => $journal->id,
                "akun" => $value["id"],
                "posisi_akun" => $value["debit"] > 0 ? "DEBIT" : "CREDIT",
                "deskripsi" => $value["description"],
                "jumlah" => $value["debit"] > 0 ? $value["debit"] : $value["credit"],
            ]);
        }

        $journal->load("journalAkuns");
        return $journal;
    }
}
