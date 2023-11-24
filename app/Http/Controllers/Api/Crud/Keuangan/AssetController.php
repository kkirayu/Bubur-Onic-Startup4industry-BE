<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\AssetService;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;


class AssetController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Asset();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new AssetService($this->model(), $this->user);
    }



    #[Route(method: ['POST'],  uri: '{asset_id}/post')]
    public function postAsset(): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->postAsset();

        return $this->single($model);
    }

    #[Route(method: ['POST'],  uri: '{asset_id}/un-post')]
    public function unPostAsset (): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->unPostAsset();

        return $this->single($model);
    }

    #[Route(method: ['POST'],  uri: '{asset_id}/depresiasi')]
    public function depresiasiAsset (): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->depresiasiAsset();

        return $this->single($model);
    }



}
