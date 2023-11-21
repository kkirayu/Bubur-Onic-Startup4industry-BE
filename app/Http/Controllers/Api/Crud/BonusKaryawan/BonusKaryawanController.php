<?php

namespace App\Http\Controllers\Api\Crud\BonusKaryawan;

use Illuminate\Http\Request;
use Laravolt\Crud\CrudModel;
use App\Models\BonusKaryawan;
use Laravolt\Crud\CrudService;
use App\Models\ProfilPegawai;
use Laravolt\Crud\ApiCrudController;
use App\Services\BonusKaryawan\BonusKaryawanService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller; // Import the Controller class
use Illuminate\Support\Facades\Validator; // Import the Validator class
use Illuminate\Validation\ValidationException; // Import the ValidationException class

class BonusKaryawanController extends ApiCrudController
{
    use ValidatesRequests;

    public function model(): CrudModel
    {
        return new BonusKaryawan();
    }

    /**
     * @return CrudService
     */
    public function service(): CrudService
    {
        return new BonusKaryawanService($this->model(), $this->user);
    }

    public function createBonus(Request $request)
    {
        try {
            $this->validate($request, [
                'bulan' => 'required|integer',
                'tahun' => 'required|integer',
                'id_profile_pegawai' => 'required|integer|exists:profile_pegawais,id',
                'total_bonus' => 'required|numeric',
                'alasan_bonus' => 'required|string',
                'bukti_bonus' => 'required|string',
                'total_diambil' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()], 422);
        }

        $profile = ProfilPegawai::find($request->id_profile_pegawai);

        if (!$profile) {
            return response()->json(['message' => 'Profile Pegawai not found'], 404);
        }

        $bonus = $this->service()->createBonus($request->all());

        $bonusData = $bonus->toArray();
        return response()->json(['message' => 'Bonus Karyawan berhasil dibuat', 'data' => $bonusData], 201);
    }
}
