<?php

namespace App\Services\Saas;

use App\Http\Requests\CreatePerusahaanRequest;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRoleCabang;
use Illuminate\Support\Facades\DB;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class PerusahaanService extends CrudService
{

    function registerPerusahaan(CreatePerusahaanRequest $request): CrudModel
    {

        $perusahaan = DB::transaction(function () use ($request) {

            $perusahaan = Perusahaan::create([
                "nama" => $request->nama,
                "kode_perusahaan" => "PERU" . date('YmdHis'),
                "status_perusahaan" => "AKTIF",
                "alamat" => $request->alamat,
                "domain_perusahaan" => $request->domain,
            ]);

            $cabang = $perusahaan->cabang()->create([
                "nama" => $request->cabang['nama'],
                "alamat" => $request->cabang['alamat'],
                "kode_cabang" =>  "CAB" . date('YmdHis'),
                "perusahaan_id" => $perusahaan->id,
            ]);

            $owner = User::create([
                "name" => $request->owner['nama'],
                "email" => $request->owner['email'],
                "password" => bcrypt($request->owner['password']),
            ]);

            $roleOwner = Role::where('name', 'owner')->first();

            $user_role_cabang = new  UserRoleCabang();
            $user_role_cabang->cabang_id = $cabang->id;
            $user_role_cabang->user_id = $owner->id;
            $user_role_cabang->perusahaan_id = $perusahaan->id;

            return  $perusahaan;


            
        });





        return $perusahaan;
    }
}
