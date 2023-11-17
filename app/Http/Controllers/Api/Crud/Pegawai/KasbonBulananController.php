<?php

namespace App\Http\Controllers\Api\Crud\Pegawai;

use App\Models\KasbonBulanan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\ApiCrudController;
use Laravolt\Crud\CrudModel;
use App\Services\Pegawai\KasbonBulananService;
use Laravolt\Crud\CrudService;


class KasbonBulananController extends ApiCrudController
{
    public function model(): CrudModel
    {
        return new KasbonBulanan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new KasbonBulananService($this->model(), $this->user);
    }

    public function updateStatus(Request $request, $id)
    {
        $model = KasbonBulanan::find($id);

        if (!$model) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:CAIR',
        ]);

        if ($model->status === 'POSTING') {
            $model->status = $request->input('status');
            $model->save();

            return $this->single($model);
        } else {
            return response()->json(['error' => 'Invalid status update'], 400);
        }
    }

}
