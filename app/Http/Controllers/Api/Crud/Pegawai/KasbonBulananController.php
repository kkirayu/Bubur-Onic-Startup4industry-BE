<?php

namespace App\Http\Controllers\Api\Crud\Pegawai;

use App\Models\KasbonBulanan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Pegawai\KasbonBulananService;
use Laravolt\Crud\CrudService;


class KasbonBulananController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new KasbonBulanan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new KasbonBulananService($this->model(), $this->user);
    }


}
