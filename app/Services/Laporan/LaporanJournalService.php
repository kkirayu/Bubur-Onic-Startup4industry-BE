<?php

namespace App\Services\Laporan;

use App\Models\Journal;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LaporanJournalService
{
    public function  index(HttpRequest $request): Collection
    {
        $journal = Journal::query();

        $perusahaan_id = $request->company;
        $type = $request->type;
        $start = $request->start;
        $end = $request->end;


        $journal->select('journals.*');
        if ($perusahaan_id != null) {
            $journal = $journal->where('journals.perusahaan_id', $perusahaan_id);
        }

        if ($type != "all") {
            $journal = $journal->where('journals.transaction_type', $type);
        }

        if ($start != null && $end != null) {
            $start = date('Y-m-d', strtotime($start));
            $end = Carbon::createFromFormat('d/m/Y', $end)->format('Y-m-d');
            $journal = $journal->where('journals.tanggal_transaksi', ">=",  $start)
                ->where('journals.tanggal_transaksi', "<=",  $end);
        }


        





        $journal = $journal->with(["journalAkuns.akun",  "postedByData"])->get();
        
        return $journal;
    }
}
