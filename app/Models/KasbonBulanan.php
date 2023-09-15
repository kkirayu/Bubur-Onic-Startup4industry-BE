<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class KasbonBulanan extends CrudModel
{
    protected $table = 'kasbon_bulanans';

    protected string $path = "/api/pegawai/kasbon-bulanan";

}
