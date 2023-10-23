<?php

namespace App\Http\Controllers\Api\Crud\Journal;

use App\Http\Requests\CreateJournalRequest;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Journal\JournalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudService;
use Laravolt\Crud\Response\DeleteFail;
use Laravolt\Crud\Response\DeleteSuccess;
use Laravolt\Crud\Response\NotFound;
use Spatie\RouteDiscovery\Attributes\Route;

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



    #[Route(method: ['POST'])]
    public function createJournal(CreateJournalRequest $createJournalRequest): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->createJournal($createJournalRequest);

        return $this->single($model);
    }


    #[Route(method: ['POST'],  uri: '{journalId}/post')]
    public function postJournal($journalId): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->postJournal($journalId);

        return $this->single($model);
    }

    #[Route(method: ['POST'],  uri: '{journalId}/un-post')]
    public function unPostJournal($journalId): JsonResource
    {

        $this->guard("CREATE");
        $model = $this->service->unPostJournal($journalId);

        return $this->single($model);
    }


    /**
     * DELETE. Data
     */
    public function destroy(mixed $id): JsonResource
    
    {
        try {
            $deleted = $this->service->delete($id);
            return $deleted ? new DeleteSuccess(null) : new DeleteFail(null);
        } catch (ModelNotFoundException $e) {
            return new NotFound($e);
        }
    }
}
