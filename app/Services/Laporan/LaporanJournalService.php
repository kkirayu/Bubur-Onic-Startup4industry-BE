<?php

namespace App\Services\Laporan;

use App\Models\Journal;
use App\Services\Odoo\OdooApiService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LaporanJournalService
{
    public function  index(HttpRequest $request): Collection
    {


        $data = new OdooApiService();

        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');

        $data = $data->getJournalList([
            ['date', '>=', "$start",],
            ['date', '<=', "$end",],
            ['state', '=', 'posted'],
        ]);
        return  collect($data);
    }
}
