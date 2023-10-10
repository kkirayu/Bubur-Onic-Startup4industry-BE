<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\Contracts\UpdateRequestContract;

class DataKaryawanRequest extends FormRequest implements StoreRequestContract, UpdateRequestContract
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required',

            'perusahaan_id' => 'required|integer',
            'cabang_id' => 'required|integer',
            // 'kode_pegawai' => 'required|unique:profile_pegawais,kode_pegawai',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'status_kawin' => 'required|boolean',
            'nomor_ktp' => 'nullable',
            'npwp' => 'nullable',
            'gaji_pokok' => 'required|numeric',
            'uang_hadir' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
            'tunjangan_tambahan' => 'required|numeric',
            'extra_rajin' => 'required|numeric',
            'thr' => 'required|numeric',
            'tunjangan_lembur' => 'required|numeric',
            'quota_cuti_tahunan' => 'required|integer',
            'team_id' => 'required|integer',
        ];
    }
}
