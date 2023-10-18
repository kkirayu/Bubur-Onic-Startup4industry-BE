<?php

namespace App\Http\Controllers\Api\Crud\Laporan;

use App\Services\Laporan\LaporanNeracaService;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;

class LaporanNeracaController 
{


    use CanFormatResource;

    public function index(HttpRequest  $request) : JsonResource
    {
        return $this->collection((new LaporanNeracaService())->index($request));
    }
    


}
