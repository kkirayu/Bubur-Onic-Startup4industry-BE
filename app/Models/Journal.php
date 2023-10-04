<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;
use Laravolt\Crud\Spec\BaseTableValue;

class Journal extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'journals';

    protected string $path = "/api/journal/journal";



    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


    function postedByData() : BelongsTo {
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

    

}
