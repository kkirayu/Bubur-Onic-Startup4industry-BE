<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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
            "invoice_date" => "required|date",
            "due_date" => "required|date",
            "customer_id" => "required|integer",
            "total" => "required|numeric",
            "desc" => "required|string",
            "invoice_details" => "required|array",
            "invoice_details.*.product_id" => "required|integer",
            "invoice_details.*.qty" => "required|integer",
            "invoice_details.*.account_id" => "required|integer",
            "invoice_details.*.price" => "required|integer",
            "invoice_details.*.total" => "required|integer",
            "invoice_details.*.discout" => "nullable|integer",
            "invoice_details.*.tax" => "nullable|integer",
            "invoice_details.*.subtotal" => "required|integer",
            "invoice_details.*.description" => "nullable|string",

            //
        ];
    }
}
