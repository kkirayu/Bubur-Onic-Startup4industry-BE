<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;
use Laravolt\Crud\Spec\BaseTableValue;

class KategoriAkun extends CrudModel
{
    use HideCompanyTrait ,  SoftDeletes;
    protected $table = 'kategori_akuns';

    protected string $path = "/api/akun/kategori-akun";


    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


    function parent() : BelongsTo {
        return $this->belongsTo(KategoriAkun::class,  "parent_kategori_akun");
    }
    public function getParent_kategori_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/kategori-akun?show_all=true", "get", "id", "nama");
    }


    public function tableValueMapping(): array
    {
        return [
            new BaseTableValue("parent_kategori_akun", "hasOne", "parent", "nama"),
        ];
    }
}
