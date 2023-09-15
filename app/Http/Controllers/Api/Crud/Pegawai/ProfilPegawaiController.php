<?php

namespace App\Http\Controllers\Api\Crud\Pegawai;

use App\Models\ProfilPegawai;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Pegawai\ProfilPegawaiService;
use Laravolt\Crud\CrudService;


class ProfilPegawaiController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new ProfilPegawai();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new ProfilPegawaiService($this->model(), $this->user);
    }


}
