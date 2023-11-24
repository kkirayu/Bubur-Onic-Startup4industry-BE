<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;

class PotonganKaryawan extends CrudModel
{
    protected $table = 'potongan_karyawans';

    protected string $path = "/api/potongan-karyawan/potongan-karyawan";

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;


}
