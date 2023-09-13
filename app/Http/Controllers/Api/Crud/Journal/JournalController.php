<?php

namespace App\Http\Controllers\Api\Crud\Journal;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Journal\JournalService;
use Laravolt\Crud\CrudService;


class JournalController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new Journal();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new JournalService($this->model(), $this->user);
    }


}
