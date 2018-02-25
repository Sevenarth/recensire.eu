<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestUnitFormRequest extends FormRequest
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
            'tester_id' => 'required|exists:tester,id',
            'expires_on_time' => 'required|numeric',
            'expires_on_space' => ['required', Rule::in(array_keys(config('testUnit.timeSpaces')))],
            'reference_url' => 'required|url',
            'review_url' => 'nullable|url',
            'amazon_order_id' => 'nullable|string',
            'refunded_amount' => 'required|numeric',
            'paypal_account' => 'nullable|email',
            'status' => ['required', Rule::in(array_keys(config('testUnit.statuses')))],
            'instructions' => 'required'
        ];
    }

    public function messages()
    {
      return [
        '*.required' => "Questo campo è obbligatorio",
        '*.url' => 'Questo campo deve essere un URL valido',
        '*.string' => 'Questo campo deve essere una stringa valida',
        'paypal_account.email' => 'L\'account PayPal deve essere una email valida',
        'refunded_amount.numeric' => "L'importo da rimborsare deve essere un numero valido",
        'status.in' => 'Lo stato scelto non è valido',
        '*.date' => 'Devi inserire una data valida',
        'tester_id.exists' => "Il tester scelto non esiste nel sistema"
      ];
    }
}
