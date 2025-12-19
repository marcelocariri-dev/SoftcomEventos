<?php
// app/Http/Requests/ParticipanteRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ParticipanteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');

        return [
            'nome' => 'required|string|max:255',
            //'cpf' => 'required|string|min:11|max:14|unique:participantes,cpf,' . $id,
            'email' => 'required|email|max:255|', //unique:participantes,email,' . $id,
            //'telefone' => 'required|string|min:10|max:15',
            //'data_nascimento' => 'required|date|before:today',
            'user_id' => 'nullable|exists:users,id',
            'ativo' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres',
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.min' => 'O CPF deve ter 11 dígitos',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Email inválido',
            'email.unique' => 'Este email já está cadastrado',
            'telefone.required' => 'O telefone é obrigatório',
            'telefone.min' => 'O telefone deve ter no mínimo 10 dígitos',
            'data_nascimento.required' => 'A data de nascimento é obrigatória',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje',
            'user_id.exists' => 'Usuário inválido'
        ];
    }

    protected function prepareForValidation()
    {
        // Remove formatação
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/[^0-9]/', '', $this->cpf)
            ]);
        }

        if ($this->has('telefone')) {
            $this->merge([
                'telefone' => preg_replace('/[^0-9]/', '', $this->telefone)
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}