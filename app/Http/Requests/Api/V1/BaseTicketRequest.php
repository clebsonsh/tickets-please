<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    /**
     * @param  array<string, mixed>  $attributesToMerge
     * @return array<string, mixed>
     */
    public function mappedAttribues(array $attributesToMerge = []): array
    {
        $attributesMap = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
            'data.relationships.author.data.id' => 'user_id',
        ];

        $attributesToUpdate = [];

        foreach ($attributesMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return array_merge($attributesToUpdate, $attributesToMerge);
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
