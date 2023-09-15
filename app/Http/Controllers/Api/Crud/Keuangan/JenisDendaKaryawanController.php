<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\JenisDendaKaryawan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\JenisDendaKaryawanService;
use Laravolt\Crud\CrudService;


class JenisDendaKaryawanController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new JenisDendaKaryawan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new JenisDendaKaryawanService($this->model(), $this->user);
    }


}
