<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestAcceptRequest extends FormRequest
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
            'paypal_account' => 'required|email|max:191',
            'amazon_order_id' => ['required', 'string', 'max:191', 'regex:/^([0-9-]+)$/'],
            'tester_notes' => 'nullable|string|max:191'
        ];
    }

    public function messages()
    {
      return [
        '*.required' => 'Questo campo è obbligatorio.',
        'paypal_account.email' => "L'account PayPal deve essere un indirizzo email valido.",
        'amazon_order_id.string' => "Il numero di ordine Amazon deve essere una stringa valida.",
        'amazon_order_id.regex' => "Il numero di ordine Amazon deve essere composto da numberi e trattini, come mostrato nel campo.",
        'tester_notes.string' => "Le note devono essere una stringa valida."
      ];
    }
}
