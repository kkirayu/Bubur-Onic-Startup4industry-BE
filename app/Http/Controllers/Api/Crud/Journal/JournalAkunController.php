<?php

namespace App\Http\Controllers\Api\Crud\Journal;

use App\Models\JournalAkun;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Journal\JournalAkunService;
use Laravolt\Crud\CrudService;


class JournalAkunController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new JournalAkun();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new JournalAkunService($this->model(), $this->user);
    }


}
