<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class VerifikasiKasbonBulanan extends CrudModel
{
    protected $table = 'verifikasi_kasbon_bulanans';

    protected string $path = "/api/pegawai/verifikasi-kasbon-bulanan";

}
