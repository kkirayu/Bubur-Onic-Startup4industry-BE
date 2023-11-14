<?php

namespace App\Http\Controllers\Api\Crud\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Product\ProductService;
use Laravolt\Crud\CrudService;


class ProductController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Product();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new ProductService($this->model(), $this->user);
    }


}
