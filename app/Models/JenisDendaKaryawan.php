<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class JenisDendaKaryawan extends CrudModel
{

    use HideCompanyTrait;
        protected $table = 'jenis_denda_karyawans';

    protected string $path = "/api/human-resource/jenis-denda-karyawan";

}
