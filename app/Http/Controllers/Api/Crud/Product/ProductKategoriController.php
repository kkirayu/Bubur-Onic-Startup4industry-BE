<?php

namespace App\Http\Controllers\Api\Crud\Product;

use App\Models\ProductKategori;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Product\ProductKategoriService;
use Laravolt\Crud\CrudService;


class ProductKategoriController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new ProductKategori();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new ProductKategoriService($this->model(), $this->user);
    }


}
