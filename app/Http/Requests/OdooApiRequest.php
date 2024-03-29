<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OdooApiRequest extends FormRequest
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
            //
            "model" => "required|string",
            "method" => "required|string",
            "args" => "array|nullable",
            "kwargs" => "array|nullable",
            "res_type" => "string|nullable|in:PAGINATEDLIST,RAWLIST,RECORD,STATEMENT",
        ];
    }
}
