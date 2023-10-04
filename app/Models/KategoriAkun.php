<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;

class KategoriAkun extends CrudModel
{
    protected $table = 'kategori_akuns';

    protected string $path = "/api/akun/kategori-akun";

    public function getParent_kategori_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/kategori-akun", "get", "id", "nama");
    }
}
