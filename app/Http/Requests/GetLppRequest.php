<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetLppRequest extends FormRequest
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
            "start" => ["required","date_format:Y-m-d"],
            "end" => ["required","date_format:Y-m-d"]
        ];
    }

    public function messages()
    {
        return [
            "start.required" => "tanggal start harus diisi",
            "start.date_format" => "format tanggal start tidak sesuai ",
            "end.required" => "tanggal selesai harus diisi",
            "end.date_format" => "format tanggal selesai tidak sesuai "
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
