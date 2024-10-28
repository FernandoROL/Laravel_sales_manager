<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestClient extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $request = [];
        if ($this->method() == "POST" || $this->method() == "PUT") {
            $request = [
                "name" => 'required',
                "email" => 'required',
                "adress" => 'required',
                "public-place" => 'required',
                "zip-code" => 'required',
                "neighborhood" => 'required',
            ];
        }
        return $request;
    }
}