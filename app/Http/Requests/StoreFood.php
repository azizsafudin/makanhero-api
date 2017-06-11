<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFood extends FormRequest
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
            'title' => 'required|max:128',
            'loc_name'=>'required|max:255',
            'lat'   => 'required|between:-90,90',
            'lng'   => 'required|between:-180,180',
            'body'  => 'nullable|max:255',
            'type'  => 'required|max:255',
            'expiry'=> 'required|date_format:Y-m-d H:i:s'
        ];
    }
}
