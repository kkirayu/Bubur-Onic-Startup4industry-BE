<?php

namespace App\Http\Controllers\Api\Crud\Odoo;

use App\Http\Requests\OdooApiRequest;
use App\Services\Odoo\OdooApiService;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;
use Spatie\RouteDiscovery\Attributes\Route;

class OdooApiController
{


    use CanFormatResource;



    #[Route(method: ['POST'])]
    public function index(OdooApiRequest  $request): JsonResource
    {
        $method = $request->get('method');
        $model = $request->get('model');
        $params = $request->get('params')?? [];
        $kwarg = $request->get('kwarg') ?? [];

        return $this->single((new OdooApiService())->executeKw($model, $method, $params, $kwarg));
    }
}
