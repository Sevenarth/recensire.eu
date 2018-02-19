<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Seller;

class SellerFormRequest extends FormRequest
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
            'nickname' => 'nullable|string',
            'name' => 'string|required',
            'email' => 'email|required',
            'facebook' => 'nullable|numeric',
            'wechat' => 'nullable|string',
            'profile_image' => 'nullable|url'
        ];
    }

    public function messages()
    {
      return [
          'nickname.string' => "Lo pseudonimo deve essere una stringa valida.",
          'name.string' => "Il nome deve essere una stringa valida.",
          'name.required' => "Questo campo non pu&ograve; essere vuoto.",
          'email.required' => "Questo campo non pu&ograve; essere vuoto.",
          'email.email' => "L'indirizzo email non &egrave; valido.",
          'facebook.numeric' => "L'ID di Facebook deve essere numerico.",
          'wechat.string' => "L'ID di WeChat deve essere una stringa valida.",
          'profile_image.url' => "L'URL dell'immagine del profilo non &egrave; valido."
      ];
    }
}
