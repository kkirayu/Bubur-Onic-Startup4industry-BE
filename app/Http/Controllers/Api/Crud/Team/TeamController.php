<?php

namespace App\Http\Controllers\Api\Crud\Team;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Team\TeamService;
use Laravolt\Crud\CrudService;


class TeamController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Team();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new TeamService($this->model(), $this->user);
    }


}
