<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
         return [
             'name' => 'string|required',
             'company_name' => 'string|required',
             'company_registration_no' => 'nullable|string',
             'VAT' => 'nullable|string',
             'country' => 'string|required',
             'seller_id' => 'exists:seller,id',
             'url' => 'nullable|url'
         ];
     }

     public function messages()
     {
       return [
           'name.string' => "Il nome deve essere una stringa valida.",
           'name.required' => "Questo campo non pu&ograve; essere vuoto.",
           'company_name.required' => "Questo campo non pu&ograve; essere vuoto.",
           'company_name.string' => "Il nome impresa deve essere una stringa valida.",
           'company_registration_no.string' => "Il numero registrazione impresa deve essere una stringa valida.",
           'VAT.string' => "La partita IVA deve essere una stringa valida.",
           'seller_id.exists' => "Il venditore selezionato non &egrave; valido.",
           'country.required' => "Questo campo non pu&ograve; essere vuoto.",
           'country.string' => "Il paese di registrazione deve essere una stringa valida.",
           'url.url' => "L'indirizzo web del negozio non &egrave; valido."
       ];
     }
}
