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
          'name' => 'required|string',
          'email' => 'required|email',
          'amazon_profiles.*' => 'required|url',
          'facebook_profiles.*' => 'nullable|present|string',
          'wechat' => 'nullable|string',
          'profile_image' => 'nullable|url'
        ];
    }

    public function messages()
    {
      return [
        'name.required' => 'Questo campo è obbligatorio',
        'email.required' => 'Questo campo è obbligatorio',
        'amazon_profiles.*.required' => 'Questo campo è obbligatorio',
        'amazon_profiles.*.url' => 'Questo campo deve essere un URL valido',
        'facebook_profiles.*.string' => 'Questo campo deve essere una stringa valida',
        'wechat.string' => 'Questo campo deve essere una stringa valida',
        'profile_image.url' => 'Questo campo deve essere un URL valido'
      ];
    }
}