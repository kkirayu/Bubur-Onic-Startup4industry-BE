<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Input\Selection\StaticSelection;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;
use Laravolt\Crud\Spec\BaseTableValue;

class Akun extends CrudModel
{

    use HideCompanyTrait, SoftDeletes;
    protected $table = 'akuns';

    protected string $path = "/api/akun/akun";

    
    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


    function kategori_akun() : BelongsTo {
        return $this->belongsTo(KategoriAkun::class);
    }
    
    function parent() : BelongsTo {
        return $this->belongsTo(Akun::class,  "parent_akun");
    }

    public function getKategori_akun_idSelection()
    {
        return new UrlForeignSelection("/api/akun/kategori-akun", "get", "id", "nama");
    }
    public function getParent_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/akun", "get", "id", "nama");
    }

    public function tableValueMapping(): array
    {
        return [
            new BaseTableValue("kategori_akun_id", "hasOne", "kategori_akun", "nama"),
            new BaseTableValue("parent_akun", "hasOne", "parent", "nama"), 
            new BaseTableValue("is_kas", "hasOne", "is_kas_selection", "label")
        ];
    }


    public function getSaldoAt( $start, $end)
    {
        $journalAkun = JournalAkun::where("akun", $this->id)->join("journals", "journals.id", "=", "journal_akuns.journal_id");
        if ($start) {
            $journalAkun = $journalAkun->whereDate("journals.tanggal_transaksi", ">=", $start);
        }
        if ($end) {
            $journalAkun = $journalAkun->whereDate("journals.tanggal_transaksi", "<=", $end);
        }

        $journalAkuns = $journalAkun->get();

        $totalDebit = $journalAkuns->filter(function ($item) {
            return $item->posisi_akun == "DEBIT";
        })->sum("jumlah");
        $totalKredit = $journalAkuns->filter(function ($item) {
            return $item->posisi_akun == "CREDIT";
        })->sum("jumlah");
        return $totalDebit - $totalKredit;
    }



    public function getIs_kasSelection()
    {

        return new StaticSelection([
            [
                "key" => "1",
                "label" => "Ya",
            ],
            [
                "key" => "0",
                "label" => "Bukan",
            ]
        ], "key", "label");
    }
    
}
