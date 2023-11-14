<?php

namespace App\Http\Controllers\Api\Crud\Product;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Product\BrandService;
use Laravolt\Crud\CrudService;


class BrandController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Brand();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new BrandService($this->model(), $this->user);
    }


}
