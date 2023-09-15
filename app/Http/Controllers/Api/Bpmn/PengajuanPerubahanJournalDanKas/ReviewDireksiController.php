<?php

namespace App\Http\Controllers\Api\Bpmn\PengajuanPerubahanJournalDanKas;

use App\Models\Bpmn\PengajuanPerubahanJournalDanKas\ReviewDireksi;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\UserTaskController;
use Laravolt\Crud\UserTaskModel;
use App\Services\Bpmn\PengajuanPerubahanJournalDanKas\ReviewDireksiService;
use Laravolt\Crud\UserTaskService;


class ReviewDireksiController extends UserTaskController
{
    public function model(): UserTaskModel
    {
        return new ReviewDireksi();
    }

    /**
     * @return UserTaskService
     */
    public function service(): UserTaskService
    {
        return new ReviewDireksiService($this->model(), $this->user);
    }


}
