<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePerusahaanRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "nama" => "required|unique:perusahaans,nama",
            "alamat" => "required",
            "domain" => "required",
            "cabang.nama" => "required",
            "cabang.alamat" => "required",
            "cabang.kode" => "required",
            "owner.nama" => "required",
            "owner.email" => "required",
            "owner.password" => "required",
            
        ];
    }
}
