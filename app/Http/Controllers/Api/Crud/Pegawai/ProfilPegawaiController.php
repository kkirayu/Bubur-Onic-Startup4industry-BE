<?php

namespace App\Http\Controllers\Api\Crud\Pegawai;

use App\Http\Requests\DataKaryawanRequest;
use App\Models\ProfilPegawai;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Pegawai\ProfilPegawaiService;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

class ProfilPegawaiController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new ProfilPegawai();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new ProfilPegawaiService($this->model(), $this->user);
    }

    /**
     * POST. Create Data
     */

     #[Route(method: ['POST', 'PUT'])]
    public function store_with_user(DataKaryawanRequest  $request): JsonResource
    {
//        $this->authorize('create', $this->model());

        $model = $this->service->createWithUser($request);
        $model->refresh();

        return $this->single($model);
    }


}
