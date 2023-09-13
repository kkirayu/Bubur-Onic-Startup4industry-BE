<?php

namespace App\Http\Controllers\Api\Crud\Saas;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Saas\CabangService;
use Laravolt\Crud\CrudService;


class CabangController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Cabang();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new CabangService($this->model(), $this->user);
    }


}
