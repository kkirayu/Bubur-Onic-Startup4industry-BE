<?php

namespace App\Http\Controllers\Api\Crud\Pegawai;

use App\Models\VerifikasiKasbonBulanan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Pegawai\VerifikasiKasbonBulananService;
use Laravolt\Crud\CrudService;


class VerifikasiKasbonBulananController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new VerifikasiKasbonBulanan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new VerifikasiKasbonBulananService($this->model(), $this->user);
    }


}
