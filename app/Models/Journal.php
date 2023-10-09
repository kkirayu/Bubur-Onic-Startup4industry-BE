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
