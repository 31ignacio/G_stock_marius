<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class saveProduitRequest extends FormRequest
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
            'ref' => 'required',
            'libelle' => 'required|string',
            'date' => 'required|',
            'quantite' => 'required'


        ];
    }

    public function messages()
    {
        return [
            'ref.required' => 'La référence est requis',
            'libelle.required' => 'Le produit est requis',
            'quantite.required' => 'Le quantite est requis',
            'date.required' => 'La date est requis'

        ];
    }
}
