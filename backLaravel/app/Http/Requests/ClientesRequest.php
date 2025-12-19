<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

 
    public function rules()
    {
        return [
            "nome" => "required",
            "cpf" => "required|max:11",
            "email" => "required|email",
            "telefone" => "required|max:12"
            
        ];
    }
}
