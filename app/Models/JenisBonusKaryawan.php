<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class JenisBonusKaryawan extends CrudModel
{


    use HideCompanyTrait;
    protected $table = 'jenis_bonus_karyawans';

    protected string $path = "/api/human-resource/jenis-bonus-karyawan";

}
