<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Http;
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_name'=>'required|string|max:100|unique:stores,name',
            'name'=>'required|string|max:50',
            'email'=>'required|email:rfc,dns|unique:users,email',
            'password'=>'required|string|min:8|confirmed',
        ];
    }
}
