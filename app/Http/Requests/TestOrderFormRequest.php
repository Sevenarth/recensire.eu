<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestOrderFormRequest extends FormRequest
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
          'quantity' => 'required|numeric',
          'fee' => 'nullable|numeric',
          'description' => 'nullable|present'
      ];
    }

    public function messages()
    {
      return [
        'quantity.required' => "Questo campo &egrave; obbligatorio.",
        'fee.required' => "Questo campo &egrave; obbligatorio.",
        'quantity.numeric' => 'Questo campo deve essere numerico.',
        'fee.numeric' => 'Questo campo deve essere numerico.',
        'description.present' => 'Questo campo deve essere presente.'
      ];
    }
}
