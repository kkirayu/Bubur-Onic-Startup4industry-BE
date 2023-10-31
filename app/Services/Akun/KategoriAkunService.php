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
        $parent_id = request()->parent_id;
        $data = $this->model->newQuery()->where("parent_kategori_akun",  "!=",  $parent_id)->where("perusahaan_id", $perusahaan)->where("cabang_id", $cabang)->get();
        return $data;
    }
    function allowedParentAkun()
    {
        $perusahaan = request()->perusahaan_id;
        $cabang = request()->cabang_id;
        $parent_id = request()->parent_id;
        $data = $this->model->newQuery()->where("parent_kategori_akun",  null)->where("perusahaan_id", $perusahaan)->where("cabang_id", $cabang)->get();
        return $data;
    }
}
