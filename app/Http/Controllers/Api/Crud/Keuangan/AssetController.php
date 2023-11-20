<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\AssetService;
use Laravolt\Crud\CrudService;


class AssetController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Asset();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new AssetService($this->model(), $this->user);
    }


}
