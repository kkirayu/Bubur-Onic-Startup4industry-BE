<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Input\BaseCustomFieldAtribute;
use Laravolt\Crud\Input\BaseUploadFileFieldAtribute;
use Laravolt\Crud\Input\Selection\StaticSelection;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;
use Laravolt\Crud\Spec\BaseTableValue;

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
        return $this->belongsTo(User::class,  "posted_by");
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

    public function getJournalWithAkuns(Akun $akun) {

        $journalAkun = Journal::join("journal_akuns", "journal_akuns.journal_id", "=", "journals.id")
        ->where("journals.posted_at" , "!=", null)
        ->where("journal_akuns.akun", $akun->id)->with(['akun_instance'])->get();
        return $journalAkun;
    }
    public function getJournalLawan(Akun $akun) {
        $journalAkun = $this->getJournalWithAkuns($akun);
        $journalAkun = $journalAkun->where("journal_akuns.akun", "!=", $akun->id);
        return $journalAkun;
    }



    function akun_instance(): BelongsTo
    {
        return $this->belongsTo(Akun::class,  "akun");
    }
}
