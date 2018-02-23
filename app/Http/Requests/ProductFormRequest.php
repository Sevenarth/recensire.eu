<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
            'title' => 'required|string',
            'brand' => 'required|string',
            'ASIN' => 'required|string',
            'description' => 'nullable|present',
            'URL' => 'required|url',
            'images.*' => 'nullable|url',
            'categories.*' => 'nullable|exists:category,id',
            'tags' => 'nullable|string'
        ];
    }

    public function messages()
    {
      return [
        'title.required' => 'Questo campo non pu&ograve; essere vuoto.',
        'brand.required' => 'Questo campo non pu&ograve; essere vuoto.',
        'ASIN.required' => 'Questo campo non pu&ograve; essere vuoto.',
        'ASIN.unique' => "Questo prodotto &egrave; gi&agrave; presente nel sistema.",
        'URL.required' => 'Questo campo non pu&ograve; essere vuoto.',
        'title.string' => 'Il nome del prodotto deve essere una stringa valida.',
        'brand.string' => 'Il marchio deve essere una stringa valida.',
        'ASIN.string' => 'L\'ASIN deve essere una stringa valida.',
        'URL.url' => 'Il collegamento al prodotto deve essere un URL valido.',
        'images.*.url' => 'Questa immagine deve avere un URL valido.',
        'categories.*.exists' => 'Alcune categorie non esistono',
        'tags' => 'IL campo delle etichette deve essere una stringa valida.'
      ];
    }

    public function withValidator($validator)
    {
        $product = $this->route('product');

        $validator->sometimes('ASIN', 'unique:product,ASIN', function ($input) use($product) {
          if(!empty($product) && $product->ASIN == $input->get('ASIN'))
            return false;
            
          return true;
        });
    }
}
