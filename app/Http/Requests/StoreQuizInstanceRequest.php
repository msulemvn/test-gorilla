<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreQuizInstanceRequest extends FormRequest
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
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'assigned_to' => 'required|integer|exists:students,id',
            'duration' => 'required|integer|min:1',
            'scheduled_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'deadline_at' => 'required|date_format:Y-m-d H:i:s|after:now',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponse::error('An error occured, failed adding quiz', 422));
    }
}
