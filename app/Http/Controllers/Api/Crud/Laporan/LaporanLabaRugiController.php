<?php

namespace App\Http\Controllers\Api\Crud\Laporan;

use App\Services\Laporan\LaporanLabaRugiService;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;

class LaporanLabaRugiController 
{


    use CanFormatResource;

    public function index(HttpRequest  $request) : JsonResource
    {
        return $this->collection((new LaporanLabaRugiService())->index($request));
    }
    


}
