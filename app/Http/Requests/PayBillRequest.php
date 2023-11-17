<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayBillRequest extends FormRequest
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
            "bills" => "array|required",
            "bills.*.bill_id" => "integer|required",
            "bills.*.amount_paid" => "integer|required"
        ];
    }
}
