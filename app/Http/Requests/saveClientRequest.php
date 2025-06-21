<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class saveClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'telephone' => 'required',
            'nom' => 'required|string',
            'ville' => 'required|string',
            'societe' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'telephone.required' => 'Le téléphone est requis',
            'ville.required' => 'La ville est requis',
            'nom.required' => 'Le nom est requis',
            'societe.required' => 'La rqison sociale est requis'

        ];
    }
}
