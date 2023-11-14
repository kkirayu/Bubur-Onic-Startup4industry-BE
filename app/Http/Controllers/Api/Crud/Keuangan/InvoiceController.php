<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\InvoiceService;
use Laravolt\Crud\CrudService;


class InvoiceController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Invoice();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new InvoiceService($this->model(), $this->user);
    }


}
