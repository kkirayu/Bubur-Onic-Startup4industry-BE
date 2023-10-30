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
        $params = $request->get('args')?? [];
        $kwarg = $request->get('kwargs') ?? [];
        $res_type = $request->get('res_type') ?? "RAWLIST";

        $response = (new OdooApiService())->executeKw($model, $method, $params, $kwarg);
        dd($response);
        if ($res_type == "PAGINATEDLIST") {
            return $this->collection($response);
        }
        return $this->single($response);
    }
}
