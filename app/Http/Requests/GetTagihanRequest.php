<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetTagihanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "nomor" => ["required","exists:datasource.customer,nosam"],
        ];
    }

    public function messages()
    {
        return [
            "nomor.required" => "Nomor pelanggan tidak ditemukan",
            "nomor.exists" => "Nomor pelanggan tidak terdaftar"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => null,
            'message' => $validator->errors()->first('nomor')
        ], 422));
    }
}
