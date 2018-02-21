<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
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
            'image' => 'required|image'
        ];
    }

    public function messages() {
      return [
        'image.required' => "Devi selezionare una immagine per continuare.",
        'image.image' => "Devi selezionare una immagine valida."
      ];
    }
}
