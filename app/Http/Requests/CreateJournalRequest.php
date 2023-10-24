<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJournalRequest extends FormRequest
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

            "posted_at" => "nullable|date",
            "posted_by" => "nullable|integer",
            "deskripsi" => "nullable|string",
            "tanggal_transaksi" => "required|date",
            "judul" => "required|string",
            "akuns" => "required|array",
            "akuns.*.id" => "required|integer",
            "akuns.*.debit" => "required|integer",
            "akuns.*.credit" => "required|integer",

            //
        ];
    }
}
