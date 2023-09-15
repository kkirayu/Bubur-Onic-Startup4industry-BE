<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class JenisDendaKaryawan extends CrudModel
{
    protected $table = 'jenis_denda_karyawans';

    protected string $path = "/api/keuangan/jenis-denda-karyawan";

}
