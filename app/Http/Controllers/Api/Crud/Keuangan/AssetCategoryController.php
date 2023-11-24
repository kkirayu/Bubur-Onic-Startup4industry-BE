<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\AssetCategoryService;
use Laravolt\Crud\CrudService;


class AssetCategoryController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new AssetCategory();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new AssetCategoryService($this->model(), $this->user);
    }


}
