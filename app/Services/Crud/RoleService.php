<?php

namespace App\Services\crud;

use App\Http\Requests\AddPermissionToRoleRequest;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\RoleModules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\Contracts\UpdateRequestContract;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Laravolt\Crud\Sys\ActivityLog\AkActivityLog;
use Ramsey\Uuid\Uuid;

class RoleService extends CrudService
{
    public function beforeCreateHook(StoreRequestContract|FormRequest $request)
    {
        return collect($request)->except('module_id')->toArray();
    }

    public function prepareCreateData($request)
    {
        $request = $this->beforeCreateHook($request);

        return $request;
    }

    public function create(StoreRequestContract|FormRequest $request): CrudModel
    {
        $validated = $this->prepareCreateData($request);
        $validated = array_merge($validated, $this->transformCreateRequest($request));

        foreach ($this->model->getMediableColumns() as $column) {
            if (array_key_exists($column, $validated)) {
                unset($validated[$column]);
            }
        }

        if (! $request->has('permission') || empty($request->input('permission'))) {
            throw new \InvalidArgumentException('permission is required');
        }

        try {
            DB::beginTransaction();

            $model = $this->model->newQuery()->create([
                "name" => $validated['name'],
            ]);

            $moduleIds = collect($request->input('permission'));

            if (count($moduleIds) !== count(array_unique($moduleIds->pluck('permission_id')->toArray()))) {
                throw new \InvalidArgumentException('Each permission must be unique.');
            }

            foreach ($moduleIds->toArray() as $moduleId) {
                PermissionRole::create([
                    'id' => Uuid::uuid4()->toString(),
                    'role_id' => $model->id,
                    'permission_id' => $moduleId["permission_id"],
                    'access_level' => $moduleId["access_level"],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            throw $e;
        }

        $model->load($this->autoLoadRelations);
        $model->refresh();

        return $this->afterCreateHook($model);
    }

    public function update(mixed $id, UpdateRequestContract|FormRequest $request): CrudModel
    {
        $request = $this->beforeUpdateHook($id, $request);
        $model = $this->model
            ->newQuery()
            ->findOrFail($id);

        $validated = $request->all();

        DB::beginTransaction();

        try {
            if ($request['permission'] && ! empty($request['permission'])) {
                $newModuleIds = $request['permission'];
                PermissionRole::where('role_id', $model->id)->delete();
                foreach ($newModuleIds as $moduleId) {
                    PermissionRole::create([
                        'id' => Uuid::uuid4()->toString(),
                        'role_id' => $model->id,
                        'permission_id' => $moduleId["permission_id"],
                        'access_level' => $moduleId["access_level"],
                    ]);
                }
            }

            $model->update([
                "name" => $validated['name'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            throw $e;
        }

        $model->load($this->autoLoadRelations);
        $model->refresh();
        AkActivityLog::createCrudlog(
            $model,
            'UPDATE_DATA'
        );

        return $this->afterUpdateHook($id, $model);
    }


    function addPermission(AddPermissionToRoleRequest $addPermissionToRoleRequest) : CrudModel {
        $role =  $this->model->newQuery()->findOrFail($addPermissionToRoleRequest->role_id);
        foreach ($addPermissionToRoleRequest->permissions as $key => $value) {
            # code...
            $permission = Permission::find($value);
            if($permission){
                $role->permissions()->attach($permission->id, ['id' => Uuid::uuid4(), 'access_level' => $value]);
            }
            
        }

        $role ->load('permissions');
        return  $role;
        
    }
}
