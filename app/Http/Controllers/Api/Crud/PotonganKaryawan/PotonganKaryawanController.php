<?php

namespace App\Http\Controllers\Api\Crud\PotonganKaryawan;

use Illuminate\Http\Request;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use App\Models\PotonganKaryawan;
use Laravolt\Crud\ApiCrudController;
use Spatie\RouteDiscovery\Attributes\Route;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Services\PotonganKaryawan\PotonganKaryawanService;


class PotonganKaryawanController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new PotonganKaryawan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new PotonganKaryawanService($this->model(), $this->user);
    }


    #[Route(method: ['POST'],  uri: '{id}/update_status')]
    public function updateStatus(): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->updateStatus();

        return $this->single($model);
    }

}
