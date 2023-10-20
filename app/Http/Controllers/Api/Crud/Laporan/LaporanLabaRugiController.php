<?php

namespace App\Http\Controllers\Api\Crud\Laporan;

use App\Services\Laporan\LaporanLabaRugiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Laravolt\Crud\Traits\CanFormatResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request as HttpRequest;

class LaporanLabaRugiController
{


    use CanFormatResource;

    public function index(HttpRequest  $request): JsonResource
    {
        return $this->collection((new LaporanLabaRugiService())->index($request));
    }

    public function  export()
    {

        $data = [];
        // share data to view
        $pdf = Pdf::loadView('laporan.template', $data);

        // download PDF file with download method
        return $pdf->download('pdf_file.pdf');
    }
}
