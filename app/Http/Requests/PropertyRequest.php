<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'title.*'       => 'required|string|max:240',
            'description.*' => 'required|string',
            'status'        => 'required|numeric|boolean',
            'color'         => 'required|string|max:20',
            'row_number'    => 'required|numeric',
        ];
    }
}
