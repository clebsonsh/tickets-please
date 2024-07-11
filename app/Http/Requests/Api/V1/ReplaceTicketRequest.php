<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.author.data.id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function messages()
    {
        return [
            // Override the validation message for status to inform what status are accepted
            'data.attributes.status.in' => 'The selected data.attributes.status is invalid. Please use A, C, H, or X',
        ];
    }
}
