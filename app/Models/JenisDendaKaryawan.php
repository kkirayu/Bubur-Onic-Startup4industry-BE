<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;
use Laravolt\Crud\Spec\BaseTableValue;

class JenisDendaKaryawan extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'jenis_denda_karyawans';

    protected string $path = "/api/human-resource/jenis-denda-karyawan";



    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;

    function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class);
    }

    public function getAkun_idSelection()
    {
        return new UrlForeignSelection("/api/akun/akun", "get", "id", "nama");
    }

    public function tableValueMapping(): array
    {
        return [
            new BaseTableValue("akun_id", "hasOne", "akun", "nama"),
        ];
    }
}
