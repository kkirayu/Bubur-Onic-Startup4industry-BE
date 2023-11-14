<?php

namespace App\Http\Controllers\Api\Crud\Product;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Product\UnitService;
use Laravolt\Crud\CrudService;


class UnitController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Unit();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new UnitService($this->model(), $this->user);
    }


}
