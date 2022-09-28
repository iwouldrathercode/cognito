<?php

namespace Iwouldrathercode\Cognito\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountConfirmationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'confirmation_code' => 'required'
        ];
    }
}
