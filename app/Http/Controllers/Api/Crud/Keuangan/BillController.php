<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Http\Requests\CreateBillRequest;
use App\Http\Requests\PayBillRequest;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\BillService;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

class BillController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Bill();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new BillService($this->model(), $this->user);
    }

    #[Route(method: ['POST'])]
    public function createBill(CreateBillRequest $createBillRequest): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->createBill($createBillRequest);

        return $this->single($model);
    }

    #[Route(method: ['POST'])]
    public function payBill(PayBillRequest $payInvoiceRequest): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->payBill($payInvoiceRequest);

        return $this->single($model);
    }


    #[Route(method: ['POST'],  uri: '{bill_id}/post')]
    public function postBill(): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->postBill();

        return $this->single($model);
    }

    #[Route(method: ['POST'],  uri: '{bill_id}/un-post')]
    public function unPostBill (): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->unPostBill();

        return $this->single($model);
    }



}
