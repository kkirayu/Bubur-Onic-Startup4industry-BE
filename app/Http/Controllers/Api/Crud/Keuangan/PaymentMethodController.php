<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\PaymentMethodService;
use Laravolt\Crud\CrudService;


class PaymentMethodController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new PaymentMethod();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new PaymentMethodService($this->model(), $this->user);
    }


}
