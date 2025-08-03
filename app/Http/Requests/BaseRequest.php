<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    abstract public function authorize();
    abstract public function rules();
    public function messages()
    {
        return [
            // Override this method in subclasses if needed
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $response = response()->json([
            'status' => 'error',
            'errors' => $errors
        ], 400);

        throw new HttpResponseException($response);
    }
}
