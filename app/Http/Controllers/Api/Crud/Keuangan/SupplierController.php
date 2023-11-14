<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\SupplierService;
use Laravolt\Crud\CrudService;


class SupplierController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Supplier();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new SupplierService($this->model(), $this->user);
    }


}
