<?php

namespace App\Http\Controllers\Api\Crud\Keuangan;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\PayInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Keuangan\InvoiceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

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




    #[Route(method: ['POST'])]
    public function createInvoice(CreateInvoiceRequest $createJournalRequest): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->createInvoice($createJournalRequest);

        return $this->single($model);
    }


    #[Route(method: ['POST'],  uri: '{invoice_id}/post')]
    public function postInvoice(): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->postInvoice();

        return $this->single($model);
    }

    #[Route(method: ['POST'],  uri: '{invoice_id}/un-post')]
    public function unPostInvoice (): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->unPostInvoice();

        return $this->single($model);
    }

    #[Route(method: ['POST'])]
    public function payInvoice(PayInvoiceRequest $payInvoiceRequest): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->bayarInvoice($payInvoiceRequest);

        return $this->single($model);
    }


}
