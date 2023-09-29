<?php

namespace App\Services\Crud;

use App\Models\RoleUsers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\Contracts\UpdateRequestContract;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Laravolt\Crud\Sys\ActivityLog\AkActivityLog;
use Ramsey\Uuid\Uuid;

class UserService extends CrudService
{
    public function beforeCreateHook(StoreRequestContract|FormRequest $request)
    {
        return collect($request)->except('role_id')->toArray();
    }

    public function beforeUpdateHook(mixed $id, UpdateRequestContract|FormRequest $request)
    {
        return collect($request);
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

        // if (! $request->has('role_id') || empty($request->input('role_id'))) {
        //     throw new \InvalidArgumentException('role_id is required');
        // }

        try {
            DB::beginTransaction();

            $model = $this->model->newQuery()->create($validated);

            // $user->roles()->attach($request->input('role_id')); //use attach id table role_users not auto generate???
            // $roleIds = $request->input('role_id');

            // if (count($roleIds) !== count(array_unique($roleIds))) {
            //     throw new \InvalidArgumentException('Each role_id must be unique.');
            // }

            // foreach ($roleIds as $roleId) {
            //     RoleUsers::create([
            //         'id' => Uuid::uuid4()->toString(),
            //         'user_id' => $model->id,
            //         'role_id' => $roleId,
            //     ]);
            // }

            DB::commit();
        } catch (\Exception $e) {
            throw $e;
        }

        $model->load($this->autoLoadRelations);
        $model->refresh();
        AkActivityLog::createCrudlog(
            $model,

            'CREATE_DATA'
        );

        return $this->afterCreateHook($model);
    }

    public function update(mixed $id, UpdateRequestContract|FormRequest $request): CrudModel
    {
        $request = $this->beforeUpdateHook($id, $request);
        $model = $this->model
            ->newQuery()
            ->findOrFail($id);

        $validated = $request->all();

        $model->update(collect($validated)->toArray());

        $model->load($this->autoLoadRelations);
        $model->refresh();
        AkActivityLog::createCrudlog(
            $model,
            'UPDATE_DATA'
        );

        return $this->afterUpdateHook($id, $model);
    }

    public function delete(mixed $model): ?bool
    {
        if(request("user_deleted_reason", null) !=null) {
            $model->user_deleted_reason = request("user_deleted_reason");
            $model->save();
        }

        if ($model instanceof CrudModel) {
            return $model->delete();
        }
        AkActivityLog::createCrudlog(
            $model,
            "DELETE_DATA"
        );

        return $this->model
            ->newQuery()
            ->findOrFail($model)
            ->delete();
    }


}
