<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        //recupera o id do usuário logado
        $id = auth()->user()->id;
        return [
            'name'      => 'required|string|max:255',
            /*
             * unique:users,email,{$id},id
             * verifica se o email é único na tabela users, no campo email onde o id recuperado é igual ao id do usuário
             */
            'email'     => 'required|string|email|max:255|unique:users,email,{$id},id',
            'password'  => 'max:20',
            'image'     => 'image'
        ];
    }
}
