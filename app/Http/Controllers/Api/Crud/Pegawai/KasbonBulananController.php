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
        $request->validate([
            'status' => 'required|in:POSTING,CAIR',
        ]);

        $kasbon = KasbonBulanan::find($id);

        if (!$kasbon) {
            return response()->json(['message' => 'Kasbon tidak ditemukan'], 404);
        }

        if ($kasbon->status == 'NEW' && $request->status == 'POSTING') {
            $kasbon->status = 'POSTING';
        } elseif ($kasbon->status == 'POSTING' && $request->status == 'CAIR') {
            $kasbon->status = 'CAIR';
        } else {
            return response()->json(['message' => 'Perubahan status tidak valid'], 400);
        }

        $kasbon->save();

        return response()->json(['message' => 'Status berhasil diperbarui', 'data' => $kasbon]);
    }

}
