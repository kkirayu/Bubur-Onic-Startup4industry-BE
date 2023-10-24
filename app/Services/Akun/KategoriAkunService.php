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
        $perusahaan = request()->perusahaan_id;
        $cabang = request()->cabang_id;
        $data = $this->model->newQuery()->where("parent_kategori_akun",  "!=",  null)->where("perusahaan_id", $perusahaan)->where("cabang_id", $cabang)->get();
        return $data;
    }
}
