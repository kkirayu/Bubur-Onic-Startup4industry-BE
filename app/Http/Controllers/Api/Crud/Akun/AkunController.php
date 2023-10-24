<?php

namespace App\Http\Controllers\Api\Crud\Akun;

use App\Http\Requests\CreateAkunRequest;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Akun\AkunService;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

class AkunController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Akun();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new AkunService($this->model(), $this->user);
    }

    #[Route(method: ['POST'])]
    function createAkun(CreateAkunRequest $createAkunRequest)
    {
        $data = $this->service()->createAkun($createAkunRequest);
        return  $this->single($data);
    }
}
