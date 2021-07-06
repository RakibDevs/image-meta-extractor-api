<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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


    public function response(array $errors)
    {
        $err = ['errors' => 'Processing failed!',
                'message' => 'hi', ];

        return [
            'error' => $err,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string|in:image,url',   // type must be 'image' or 'url'
            'image' => 'required_if:type,image|mimes:jpg,jpeg,png|max:4096',
            'url' => 'required_if:type,url|url',
        ];
    }
}
