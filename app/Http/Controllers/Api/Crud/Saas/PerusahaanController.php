<?php

namespace App\Http\Controllers\Api\Crud\Saas;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Saas\PerusahaanService;
use Laravolt\Crud\CrudService;


class PerusahaanController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Perusahaan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new PerusahaanService($this->model(), $this->user);
    }


}
