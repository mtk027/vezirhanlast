<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchCreateRequest extends FormRequest
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
            'description.*' => 'required|string',
            'seo_title.*' => 'required|string|max:240',
            'seo_description.*' => 'required|string|max:240',
            'seo_url.*' => 'required|string|max:191',
            'status' => 'required|numeric|boolean',
            'lat' => 'required|string|max:100',
            'lng' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:240',
        ];
    }
}
