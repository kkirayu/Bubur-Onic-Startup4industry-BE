<?php

namespace App\Http\Controllers\Api\Crud\Kas;

use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Kas\KasService;
use Laravolt\Crud\CrudService;


class KasController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Kas();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new KasService($this->model(), $this->user);
    }


}
