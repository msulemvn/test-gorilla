<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreApplicationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:applications',
            'phone' => [
                'required',
                'numeric',
                'digits_between:8,12',
                'unique:applications'
            ],
            'attachment' => 'required|mimes:pdf,doc,docx|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'Name character limit exceeded (255).',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'Your application is already submitted.',
            'phone.required' => 'The phone field is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.digits_between' => 'Phone number must be 8-12 digits.',
            'phone.unique' => 'Your application is already submitted.',
            'attachment.required' => 'The attachment field is required.',
            'attachment.mimes' => 'Attachment must be PDF, DOC, or DOCX.',
            'attachment.max' => 'Attachment size limit exceeded (4MB).',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $this->validator->errors();

        $response =  response()->json([
            'validator object' => $errors
        ], 400);
        throw new HttpResponseException($response);
    }
}
