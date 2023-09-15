<?php

namespace App\Http\Controllers\Api\Bpmn;

use App\Models\Bpmn\PengajuanPerubahanJournalDanKas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\BpmnController;
use Laravolt\Crud\BpmnModel;
use App\Services\Bpmn\PengajuanPerubahanJournalDanKas\PengajuanPerubahanJournalDanKasService;
use Laravolt\Crud\BpmnService;


class PengajuanPerubahanJournalDanKasController extends BpmnController
{
    public function model(): BpmnModel
    {
        return new PengajuanPerubahanJournalDanKas();
    }

    /**
     * @return BpmnService
     */
    public function service(): BpmnService
    {
        return new PengajuanPerubahanJournalDanKasService($this->model(), $this->user);
    }


}
