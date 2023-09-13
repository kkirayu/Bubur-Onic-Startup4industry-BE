<?php

namespace App\Http\Controllers\Api\Crud\Saas;

use App\Models\UserRoleCabang;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Saas\UserRoleCabangService;
use Laravolt\Crud\CrudService;


class UserRoleCabangController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new UserRoleCabang();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new UserRoleCabangService($this->model(), $this->user);
    }


}
