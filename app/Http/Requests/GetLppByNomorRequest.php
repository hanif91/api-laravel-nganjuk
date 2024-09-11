<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetLppByNomorRequest extends FormRequest
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
            "tanggal" => ["required","date_format:Y-m-d"],
        ];
    }
    public function messages()
    {
        return [
            "nomor.required" => "Nomor pelanggan harus diisi",
            "nomor.exists" => "Nomor pelanggan tidak terdaftar",
            "tanggal.required" => "tanggal pembayaran harus diisi",
            "tanggal.date_format" => "format tanggal pembayaran tidak sesuai "
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => null,
            'message' => $validator->errors()->first()
        ], 422));
    }
}
