<?php

namespace App\Services\BonusKaryawan;

use App\Models\BonusKaryawan;
use App\Models\ProfilPegawai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\CrudService;

class BonusKaryawanService extends CrudService
{
    public function beforeCreateHook(FormRequest|StoreRequestContract $requestContract)
    {
        if(!ProfilPegawai::where("id" , $requestContract->id_profile_pegawai)->first()){
            throw ValidationException::withMessages([
                'id_profile_pegawai' => 'Profile Pegawai Tidak Ditemukan',
            ]);
        }
        return parent::beforeCreateHook($requestContract); // TODO: Change the autogenerated stub
    }

    function updateStatus() {
        $request = request();
        $request->validate([
            'status' => 'required|in:DIAJUKAN,DIBATALKAN',
        ]);

        $bonus = BonusKaryawan::find($request->id);

        if (!$bonus) {
            throw ValidationException::withMessages(['id' => 'Bonus Tidak Ditemukan']);
        }

        if ($bonus->status == 'BARU' && $request->status == 'DIAJUKAN') {
            $bonus->status = 'DIAJUKAN';
        }else if ($bonus->status == 'BARU' && $request->status == 'DIBATALKAN') {
            $bonus->status = 'DIBATALKAN';
        }else {
            throw ValidationException::withMessages(['status' => 'Status Bonus Tidak Valid']);
        }

        $bonus->save();

        return $bonus;
    }
}

