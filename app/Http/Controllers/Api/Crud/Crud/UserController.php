<?php

namespace App\Http\Controllers\Api\Crud\Crud;

use App\BulkActions\CreateUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Crud\UserService;
use Laravolt\Crud\CrudService;


class UserController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new User();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new UserService($this->model(), $this->user);
    }

    protected function bulkActions(): array
    {
        $actions = parent::bulkActions();
        $actions['createUsers'] = CreateUsers::class;

        return $actions;
    }

}
