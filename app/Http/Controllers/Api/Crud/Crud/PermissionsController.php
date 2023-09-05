<?php

namespace App\Http\Controllers\Api\Crud\Crud;

use App\Models\Module;
use App\Models\Permission;
use App\Services\Crud\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Crud\ModuleService;
use Laravolt\Crud\CrudService;


class PermissionsController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Permission();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new PermissionService($this->model(), $this->user);
    }


}
