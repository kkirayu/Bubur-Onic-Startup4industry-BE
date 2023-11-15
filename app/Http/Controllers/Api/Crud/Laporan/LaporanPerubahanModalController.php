<?php

namespace App\Http\Controllers\Api\Crud\Laporan;

use App\Services\Laporan\LaporanPerubahanModalService;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;

class LaporanPerubahanModalController
{


    use CanFormatResource;

    public function index(HttpRequest  $request) : JsonResource
    {
        return $this->single((new LaporanPerubahanModalService())->index($request));
    }



}
