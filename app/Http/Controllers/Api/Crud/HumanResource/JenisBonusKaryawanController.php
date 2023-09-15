<?php

namespace App\Http\Controllers\Api\Crud\HumanResource;

use App\Models\JenisBonusKaryawan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\HumanResource\JenisBonusKaryawanService;
use Laravolt\Crud\CrudService;


class JenisBonusKaryawanController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new JenisBonusKaryawan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new JenisBonusKaryawanService($this->model(), $this->user);
    }


}
