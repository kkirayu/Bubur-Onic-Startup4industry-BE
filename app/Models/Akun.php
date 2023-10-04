<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;

class Akun extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'akuns';

    protected string $path = "/api/akun/akun";

    


    public function getKategori_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/kategori-akun", "get", "id", "nama");
    }
    public function getParent_akunSelection()
    {
        return new UrlForeignSelection("/api/akun/akun", "get", "id", "nama");
    }
}
