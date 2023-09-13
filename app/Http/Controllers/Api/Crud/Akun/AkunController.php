<?php

namespace App\Http\Controllers\Api\Crud\Akun;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Akun\AkunService;
use Laravolt\Crud\CrudService;


class AkunController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Akun();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new AkunService($this->model(), $this->user);
    }


}
