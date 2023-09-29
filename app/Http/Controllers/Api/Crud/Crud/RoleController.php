<?php

namespace App\Http\Controllers\Api\Crud\crud;

use App\Http\Requests\AddPermissionToRoleRequest;
use App\Models\Role;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Crud\RoleService;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

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

    
    #[Route(method: ['POST'],uri : "{role_id}/permissions")]
    function addApermission($role_id,  AddPermissionToRoleRequest $addPermissionToRoleRequest) : JsonResource {
        
        $role = $this->service()->addPermission($addPermissionToRoleRequest);
        return $this->single($role);
    }

    

}
