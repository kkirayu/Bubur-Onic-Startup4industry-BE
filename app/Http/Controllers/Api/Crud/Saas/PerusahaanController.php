<?php

namespace App\Http\Controllers\Api\Crud\Saas;

use App\Http\Requests\CreatePerusahaanRequest;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Saas\PerusahaanService;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

class PerusahaanController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Perusahaan();
    }



    #[Route(method: ['POST'])]
    public function registerPerusahaan(CreatePerusahaanRequest $request) : JsonResource
    {
        
        $model = $this->service->registerPerusahaan($request);
        $model->refresh();

        return $this->single($model);
    }
        
    /**
     * @return CrudService
     */
    public function service(): PerusahaanService
    {
        return new PerusahaanService($this->model(), $this->user);
    }


}
