<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InscricaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $inscricaoId = $this->route('id');

        $rules = [
            'evento_id' => 'required|exists:eventos,id',
            'participante_id' => 'nullable|exists:participantes,id',
            'ingresso_id' => 'nullable|exists:ingressos,id',
            'status' => 'nullable|in:pendente,confirmado,cancelado',
            'valor_pago' => 'nullable|numeric|min:0',
        ];

        // Se não enviar participante_id, precisa enviar dados do participante
        if (!$this->has('participante_id') && $this->isMethod('POST')) {
            $rules['nome'] = 'required|string|max:255';
           // $rules['cpf'] = 'required|string|size:11|unique:participantes,cpf';
            $rules['email'] = 'required|email|max:255';
            //$rules['telefone'] = 'required|string|size:11';
            //$rules['data_nascimento'] = 'required|date|before:today';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            // Evento
            'evento_id.required' => 'O evento é obrigatório',
            'evento_id.exists' => 'Evento não encontrado',
            
            // Participante
            'participante_id.exists' => 'Participante não encontrado',
            
            // Ingresso
            'ingresso_id.exists' => 'Ingresso não encontrado',
            
            // Status
            'status.in' => 'Status inválido. Use: pendente, confirmado ou cancelado',
            
            // Valor
            'valor_pago.numeric' => 'Valor pago deve ser numérico',
            'valor_pago.min' => 'Valor pago não pode ser negativo',
            
            // Dados do participante
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres',
            
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.size' => 'O CPF deve ter 11 dígitos',
            'cpf.unique' => 'Este CPF já está cadastrado',
            
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Email inválido',
            'email.max' => 'O email deve ter no máximo 255 caracteres',
            
            'telefone.required' => 'O telefone é obrigatório',
            'telefone.size' => 'O telefone deve ter 11 dígitos',
            
            'data_nascimento.required' => 'A data de nascimento é obrigatória',
            'data_nascimento.date' => 'Data de nascimento inválida',
            'data_nascimento.before' => 'Data de nascimento deve ser anterior a hoje',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $data = [];

        // Limpar CPF (remover pontos e traços)
        if ($this->has('cpf')) {
            $data['cpf'] = preg_replace('/\D/', '', $this->cpf);
        }

        // Limpar telefone (remover espaços, parênteses, traços)
        if ($this->has('telefone')) {
            $data['telefone'] = preg_replace('/\D/', '', $this->telefone);
        }

        // Converter email para lowercase
        if ($this->has('email')) {
            $data['email'] = strtolower(trim($this->email));
        }

        // Capitalizar nome
        if ($this->has('nome')) {
            $data['nome'] = ucwords(strtolower(trim($this->nome)));
        }

        // Garantir que valor_pago seja numérico
        if ($this->has('valor_pago') && is_string($this->valor_pago)) {
            $data['valor_pago'] = floatval(str_replace(',', '.', $this->valor_pago));
        }

        if (!empty($data)) {
            $this->merge($data);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'evento_id' => 'evento',
            'participante_id' => 'participante',
            'ingresso_id' => 'ingresso',
            'status' => 'status',
            'valor_pago' => 'valor pago',
            'nome' => 'nome',
            'cpf' => 'CPF',
            'email' => 'email',
            'telefone' => 'telefone',
            'data_nascimento' => 'data de nascimento',
        ];
    }
}