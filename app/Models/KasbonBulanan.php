<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class KasbonBulanan extends CrudModel
{
    use HideCompanyTrait;
    protected $table = 'kasbon_bulanans';

    protected string $path = "/api/pegawai/kasbon-bulanan";

}
