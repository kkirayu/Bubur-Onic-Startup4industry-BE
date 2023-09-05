<?php

namespace App\Http\Controllers\Api\Crud\crud;

use App\Models\Role;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Crud\RoleService;
use Laravolt\Crud\CrudService;


class RoleController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Role();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new RoleService($this->model(), $this->user);
    }


}
