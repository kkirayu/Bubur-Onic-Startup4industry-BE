<?php

namespace App\Services\Akun;

use App\Models\Akun\KategoriAkun;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravolt\Crud\CrudService;

class KategoriAkunService extends CrudService
{

    function allowedKategoriAkun()
    {
        $data = $this->model->newQuery()->where("parent_kategori_akun",  "!=",  null)->get();
        return $data;
    }
}
