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
            'tester_id' => 'nullable|exists:tester,id',
            'expires_on_time' => 'required_if:status,0|numeric',
            'expires_on_space' => ['required_if:status,0', Rule::in(array_keys(config('testUnit.timeSpaces')))],
            'reference_url' => 'required_if:status,0|url',
            'review_url' => 'nullable|url',
            'amazon_order_id' => 'nullable|string',
            'refunded_amount' => 'nullable|numeric',
            'paypal_account' => 'nullable|email',
            'status' => ['required', Rule::in(array_keys(config('testUnit.statuses')))],
            'instructions' => 'required',
            'refunding_type' => ['required', Rule::in(array_keys(config('testUnit.refundingTypes')))],
            'tester_notes' => 'nullable|string'
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
        'expires_on_time.numeric' => 'Devi inserire un numero valido',
        'tester_id.exists' => "Il tester scelto non esiste nel sistema",
        'refunding_types.in' => 'Il tipo di rimborso scelto non è valido',
        'expires_on_space.in' => 'Il tipo di scadenza non è valido'
      ];
    }
}
