<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;

class Akun extends CrudModel
{
    protected $table = 'akuns';

    protected string $path = "/api/akun/akun";

    


    public function getParent_kategori_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/kategori-akun", "get", "id", "nama");
    }
    public function getParent_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/akun", "get", "id", "nama");
    }
}
