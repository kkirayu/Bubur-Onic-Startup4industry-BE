<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Input\BaseCustomFieldAtribute;
use Laravolt\Crud\Input\BaseUploadFileFieldAtribute;
use Laravolt\Crud\Input\Selection\StaticSelection;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;
use Laravolt\Crud\Spec\BaseTableValue;
use function PHPUnit\Framework\isEmpty;

class Journal extends CrudModel
{

    use HideCompanyTrait, SoftDeletes;

    protected $table = 'journals';

    protected string $path = "/api/journal/journal";


    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;

    public function journalAkuns(): HasMany
    {
        return $this->hasMany(JournalAkun::class, "journal_id");
    }

    function postedByData(): BelongsTo
    {
        return $this->belongsTo(User::class, "posted_by");
    }

    public function getPosted_bySelection()
    {
        return new UrlForeignSelection('/api/crud/user', "get", "id", "name");
    }


    public function tableValueMapping(): array
    {
        return [
            new BaseTableValue("posted_by", "hasOne", "posted_by_data", "name"),
        ];
    }

    public function getJournalWithAkuns(Akun $akun, $start, $end, $perusahaan_id)
    {

        $journalAkun = Journal::join("journal_akuns", "journal_akuns.journal_id", "=", "journals.id")
            ->where("journals.tanggal_transaksi", "<=", $end)
            ->where("journals.tanggal_transaksi", ">=", $start)
            ->where("journals.perusahaan_id", $perusahaan_id)
            ->where("journals.posted_at", "!=", null)
            ->where(DB::raw("(select count(id) from  journal_akuns where akun = " .  $akun->id . " and journal_id = journals.id)" ) ,  ">" , 0);

        $journalAkun = $journalAkun
            ->with(['akun_instance'])->get();
        return $journalAkun;
    }

    public function getJournalLawan(Akun $akun, $start, $end, $perusahaan_id)
    {
        $journalAkun = $this->getJournalWithAkuns($akun, $start, $end, $perusahaan_id);
        $journalAkun = $journalAkun->where("akun", "!=", $akun->id);
        return $journalAkun;
    }


    function akun_instance(): BelongsTo
    {
        return $this->belongsTo(Akun::class, "akun");
    }


    public function getSaldo($end, $perusahaan_id)
    {

        $journal = $this->getJournalAkuns($end, $perusahaan_id);


        $journal = $journal->groupBy("akun_instance.kode_akun");

        $accountSaldo = $journal->map(function ($item) {
            $sumofSaldo = collect($item)->sum(function ($item) {
                // dd($item->toArray());
                return $item['posisi_akun'] == "DEBIT" ? $item['jumlah'] : $item['jumlah'] * -1;
            });
            return $sumofSaldo;
        });
        return $accountSaldo;
    }


    public function getSaldoFromAccounts(array $accounts, $end, $perusahaan_id)
    {

        $journal = $this->getJournalAkuns($end, $perusahaan_id, $accounts);


        $journal = $journal->groupBy("akun_instance.kode_akun");

        $accountSaldo = $journal->map(function ($item) {
            $sumofSaldo = collect($item)->sum(function ($item) {
                // dd($item->toArray());
                return $item['posisi_akun'] == "DEBIT" ? $item['jumlah'] : $item['jumlah'] * -1;
            });
            return $sumofSaldo;
        });
        return $accountSaldo;
    }


    public function getSaldoFromAccountsWithRange(array $accounts, $start, $end, $perusahaan_id)
    {

        $journal = $this->getJournalAkunsWithRange($start, $end, $perusahaan_id, $accounts);


        $journal = $journal->groupBy("akun_instance.kode_akun");

        $accountSaldo = $journal->map(function ($item) {
            $sumofSaldo = collect($item)->sum(function ($item) {
                return $item['posisi_akun'] == "DEBIT" ? $item['jumlah'] : $item['jumlah'] * -1;
            });
            return $sumofSaldo;
        });
        return $accountSaldo;
    }


    public function getAkunTransaction($end, $perusahaan_id)
    {

        $journal = $this->getJournalAkuns($end, $perusahaan_id);
        $journal = $journal->groupBy("akun_instance.kode_akun");

        return $journal;
    }

    /**
     * @param $end
     * @param $perusahaan_id
     * @return JournalAkun[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getJournalAkuns($end, $perusahaan_id, $akuns = []): array|\Illuminate\Database\Eloquent\Collection
    {
        $journal = JournalAkun::whereHas("journal", function ($query) use ($end, $perusahaan_id) {
            $query->where("posted_at", "!=", null)->where("tanggal_transaksi", "<=", $end)->where("perusahaan_id", $perusahaan_id);
        })->with(["akun_instance"]);
        if ($akuns) {

            $journal = $journal->whereIn("akun", $akuns);
        }
        $journal = $journal->get();
        return $journal;
    }

    /**
     * @param $end
     * @param $perusahaan_id
     * @return JournalAkun[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getJournalAkunsWithRange($start, $end, $perusahaan_id, $akuns = []): array|\Illuminate\Database\Eloquent\Collection
    {
        $journal = JournalAkun::whereHas("journal", function ($query) use ($end, $perusahaan_id, $start) {
            $query->where("posted_at", "!=", null)
                ->where("tanggal_transaksi", "<=", $end)
                ->where("perusahaan_id", $perusahaan_id);
            if ($start) {
                $query->where("tanggal_transaksi", ">=", $start);
            }
        })->with(["akun_instance"]);


        if ($akuns) {

            $journal = $journal->whereIn("akun", $akuns);
        }
        $journal = $journal->get();
        return $journal;
    }

    public function getJournalAkunsTransactionedWith(array $akun, $start, $end, $perusahaan_id): array|\Illuminate\Database\Eloquent\Collection
    {
        $journal = $this->getJournalAkunsTransactioned($akun, $start, $end, $perusahaan_id);

        $journal = $journal->groupBy("akun_instance.kode_akun");
        return $journal;
    }

    public function getJournalAkunsTransactioned(array $akun, $start, $end, $perusahaan_id): array|\Illuminate\Database\Eloquent\Collection
    {
        $journal = JournalAkun::whereHas("journal", function ($query) use ($end, $start, $perusahaan_id) {
            $query->where("posted_at", "!=", null)
                ->where("tanggal_transaksi", "<=", $end)
                ->where("tanggal_transaksi", ">=", $start)
                ->where("perusahaan_id", $perusahaan_id)
                ->where("transaction_type", "kas");
        })
            ->
            whereNotIn("akun", $akun)
            ->with(["akun_instance"])->get();
        return $journal;
    }

}
