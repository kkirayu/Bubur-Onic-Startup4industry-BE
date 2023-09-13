<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class KategoriAkun extends CrudModel
{
    protected $table = 'kategori_akuns';

    protected string $path = "/api/akun/kategori-akun";

}
