<?php

namespace App\Http\Controllers\Api\Crud\Akun;

use App\Models\KategoriAkun;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Akun\KategoriAkunService;
use Laravolt\Crud\CrudService;


class KategoriAkunController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new KategoriAkun();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new KategoriAkunService($this->model(), $this->user);
    }


}
