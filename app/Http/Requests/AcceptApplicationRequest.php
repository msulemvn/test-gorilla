<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptApplicationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'applicationId' => 'required|numeric|integer|exists:applications,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'applicationId.required' => 'Application ID is required',
            'applicationId.numeric' => 'Application ID must be a number',
            'applicationId.exists' => 'Application ID does not exist',
        ];
    }

    /**
     * Get the validation data.
     *
     * @return array
     */
    public function validationData()
    {
        return array_merge($this->request->all(), [
            'applicationId' => $this->route('applicationId'),
        ]);
    }
}
