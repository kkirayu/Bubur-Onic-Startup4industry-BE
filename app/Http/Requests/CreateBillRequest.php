<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBillRequest extends FormRequest
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
            "bill_date" => "required|date",
            "due_date" => "required|date",
            "supplier_id" => "required|integer",
            "total" => "required|numeric",
            "desc" => "required|string",
            "bill_details" => "required|array",
            "bill_details.*.product_id" => "required|integer",
            "bill_details.*.qty" => "required|integer",
            "bill_details.*.account_id" => "required|integer",
            "bill_details.*.price" => "required|integer",
            "bill_details.*.total" => "required|integer",
            "bill_details.*.discout" => "nullable|integer",
            "bill_details.*.tax" => "nullable|integer",
            "bill_details.*.subtotal" => "required|integer",
            "bill_details.*.description" => "nullable|string",

            //
        ];
    }
}
