<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class JenisBonusKaryawan extends CrudModel
{
    protected $table = 'jenis_bonus_karyawans';

    protected string $path = "/api/human-resource/jenis-bonus-karyawan";

}
