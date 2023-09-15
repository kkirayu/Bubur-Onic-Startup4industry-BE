<?php

namespace App\Http\Controllers\Api\Bpmn\PengajuanPerubahanJournalDanKas;

use App\Models\Bpmn\PengajuanPerubahanJournalDanKas\RevisiPengajuan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\UserTaskController;
use Laravolt\Crud\UserTaskModel;
use App\Services\Bpmn\PengajuanPerubahanJournalDanKas\RevisiPengajuanService;
use Laravolt\Crud\UserTaskService;


class RevisiPengajuanController extends UserTaskController
{
    public function model(): UserTaskModel
    {
        return new RevisiPengajuan();
    }

    /**
     * @return UserTaskService
     */
    public function service(): UserTaskService
    {
        return new RevisiPengajuanService($this->model(), $this->user);
    }


}
