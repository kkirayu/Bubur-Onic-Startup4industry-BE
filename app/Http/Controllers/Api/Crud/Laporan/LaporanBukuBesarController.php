<?php

namespace App\Http\Controllers\Api\Crud\Laporan;

use App\Services\Laporan\LaporanBukuBesarService;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;

class LaporanBukuBesarController 
{


    use CanFormatResource;

    public function index(HttpRequest  $request) : JsonResource
    {
        return $this->single((new LaporanBukuBesarService())->index($request));
    }
    


}
