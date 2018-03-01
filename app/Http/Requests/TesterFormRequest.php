<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TesterFormRequest extends FormRequest
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
          'name' => 'required|string|max:191',
          'email' => 'nullable|email|max:191',
          'amazon_profiles.*' => 'nullable|present|url',
          'facebook_profiles.*' => 'nullable|present|string',
          'wechat' => 'nullable|string|max:191',
          'profile_image' => 'nullable|url|max:191'
        ];
    }

    public function messages()
    {
      return [
        'name.required' => 'Questo campo è obbligatorio',
        'email.email' => "L'indirizzo email inserito non è valido",
        'amazon_profiles.*.url' => 'Questo campo deve essere un URL valido',
        'facebook_profiles.*.string' => 'Questo campo deve essere una stringa valida',
        'wechat.string' => 'Questo campo deve essere una stringa valida',
        'profile_image.url' => 'Questo campo deve essere un URL valido'
      ];
    }
}
