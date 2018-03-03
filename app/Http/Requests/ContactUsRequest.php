<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Request;
use Validator;

class ContactUsRequest extends FormRequest
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
        Validator::extend('recaptcha', function ($message, $attribute, $rule, $parameters) {
          $recaptcha = new \ReCaptcha\ReCaptcha(config('app.recaptcha_secret_key'));
          $resp = $recaptcha->verify($attribute, Request::ip());

          if ($resp->isSuccess())
            return true;
          else
            return false;
        });

        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|recaptcha',
            'content' => 'required|string'
        ];
    }

    public function messages()
    {
      return [
        '*.required' => 'Questo campo è obbligatorio',
        '*.string' => 'Questo campo deve essere una stringa valida',
        'email.email' => 'Inserisci un indirizzo email valido',
        'g-recaptcha-response.recaptcha' => 'La validazione reCAPTCHA è fallita'
      ];
    }
}
