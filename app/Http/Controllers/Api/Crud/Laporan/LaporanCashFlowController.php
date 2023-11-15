<?php

namespace App\Http\Controllers\Api\Crud\Laporan;

use App\Services\Laporan\LaporanCashFlowService;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;

class LaporanCashFlowController 
{


    use CanFormatResource;


    public function index(HttpRequest  $request) : JsonResource
    {
        return $this->single((new LaporanCashFlowService())->index($request));
    }


}
