<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            "status" => ['required', 'string'],
            "transaction_id" => ['required', 'integer', 'unique:transactions,transaction_id'],
            "tx_ref" => ['required', 'string', 'unique:transactions,tx_ref'],
            "amount" => ['required', 'integer'],
            "product_id" => ['required', 'integer', 'exists:products,id'],
        ];
    }
}
