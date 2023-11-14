<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\CustomerService;
use Laravolt\Crud\CrudService;


class CustomerController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Customer();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new CustomerService($this->model(), $this->user);
    }


}
