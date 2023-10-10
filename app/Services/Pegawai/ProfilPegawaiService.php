<?php

namespace App\Services\Pegawai;

use App\Http\Requests\DataKaryawanRequest;
use App\Models\ProfilPegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class ProfilPegawaiService extends CrudService
{

    function createWithUser(DataKaryawanRequest $dataKaryawanRequest) : CrudModel {
       
        $user = new User();
        $user->name = $dataKaryawanRequest->name;
        $user->email = $dataKaryawanRequest->email;
        $user->password = Hash::make($dataKaryawanRequest->password);
        $user->email_verified_at = Carbon::now();
        
        $user->save();


        $profilePegawai = new ProfilPegawai();
        $profilePegawai->perusahaan_id = $dataKaryawanRequest->perusahaan_id;
        $profilePegawai->cabang_id = $dataKaryawanRequest->cabang_id;
        $profilePegawai->user_id = $user->id;
        $profilePegawai->kode_pegawai = "PGW" . date('YmdHis'). rand(100, 999);
        $profilePegawai->alamat = $dataKaryawanRequest->alamat;
        $profilePegawai->jenis_kelamin = $dataKaryawanRequest->jenis_kelamin;
        $profilePegawai->agama = $dataKaryawanRequest->agama;
        $profilePegawai->tanggal_lahir = $dataKaryawanRequest->tanggal_lahir;
        $profilePegawai->tanggal_masuk = $dataKaryawanRequest->tanggal_masuk;
        $profilePegawai->tanggal_keluar = $dataKaryawanRequest->tanggal_keluar;
        $profilePegawai->status_kawin = $dataKaryawanRequest->status_kawin;
        $profilePegawai->nomor_ktp = $dataKaryawanRequest->nomor_ktp;
        $profilePegawai->npwp = $dataKaryawanRequest->npwp;
        $profilePegawai->gaji_pokok = $dataKaryawanRequest->gaji_pokok;
        $profilePegawai->uang_hadir = $dataKaryawanRequest->uang_hadir;
        $profilePegawai->tunjangan_jabatan = $dataKaryawanRequest->tunjangan_jabatan;
        $profilePegawai->tunjangan_tambahan = $dataKaryawanRequest->tunjangan_tambahan;
        $profilePegawai->extra_rajin = $dataKaryawanRequest->extra_rajin;
        $profilePegawai->thr = $dataKaryawanRequest->thr;
        $profilePegawai->tunjangan_lembur = $dataKaryawanRequest->tunjangan_lembur;
        $profilePegawai->quota_cuti_tahunan = $dataKaryawanRequest->quota_cuti_tahunan;
        $profilePegawai->team_id = $dataKaryawanRequest->team_id;


        

        $profilePegawai->save();

        
        return  $profilePegawai;

    }

}
