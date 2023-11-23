<?php

namespace App\Http\Controllers\Api\Crud\BonusKaryawan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudModel;
use App\Models\BonusKaryawan;
use Laravolt\Crud\CrudService;
use App\Models\ProfilPegawai;
use Laravolt\Crud\ApiCrudController;
use App\Services\BonusKaryawan\BonusKaryawanService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller; // Import the Controller class
use Illuminate\Support\Facades\Validator; // Import the Validator class
use Illuminate\Validation\ValidationException;
use Spatie\RouteDiscovery\Attributes\Route;

// Import the ValidationException class

class BonusKaryawanController extends ApiCrudController
{
    use ValidatesRequests;

    public function model(): CrudModel
    {
        return new BonusKaryawan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new BonusKaryawanService($this->model(), $this->user);
    }


    #[Route(method: ['POST'],  uri: '{id}/update_status')]
    public function updateStatus(): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->updateStatus();

        return $this->single($model);
    }



}
