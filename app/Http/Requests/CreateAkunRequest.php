<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAkunRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'account_type' => 'required|string',
            'is_akun_bank' => 'required|boolean',
            'kode_akun' => 'required|string|max:255',
            'nama_akun' => 'required|string|max:255',
            'perusahaan_id' => 'required|integer',
            'cabang_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'kategori_akun.required' => 'Kategori akun harus diisi',
            'kategori_akun.integer' => 'Kategori akun harus berupa angka',
            'is_akun_bank.required' => 'Is akun bank harus diisi',
            'is_akun_bank.boolean' => 'Is akun bank harus berupa boolean',
            'kode_akun.required' => 'Kode akun harus diisi',
            'kode_akun.string' => 'Kode akun harus berupa string',
            'kode_akun.max' => 'Kode akun maksimal 255 karakter',
            'nama_akun.required' => 'Nama akun harus diisi',
            'nama_akun.string' => 'Nama akun harus berupa string',
            'nama_akun.max' => 'Nama akun maksimal 255 karakter',
            'perusahaan_id.required' => 'Perusahaan ID harus diisi',
            'perusahaan_id.integer' => 'Perusahaan ID harus berupa angka',
            'cabang_id.required' => 'Cabang ID harus diisi',
            'cabang_id.integer' => 'Cabang ID harus berupa angka',
        ];
    }
}