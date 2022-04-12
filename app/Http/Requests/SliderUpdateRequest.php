<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderUpdateRequest extends FormRequest
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
            'title.*' => 'required|string|max:240',
            'sub_title.*' => 'required|string|max:240',
            'short_description.*' => 'required|string',
            'button_title.*' => 'required|string|max:240',
            'status' => 'required|numeric|boolean',
            'release_date' => 'required',
            'row_number' => 'required|numeric',
        ];
    }
}
