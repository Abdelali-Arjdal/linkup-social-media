<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // All authenticated users can create posts
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:1', 'max:2000'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Sanitize content to prevent XSS
        if (isset($validated['content'])) {
            $validated['content'] = strip_tags($validated['content']);
        }
        
        return $validated;
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Post content is required.',
            'content.max' => 'Post content cannot exceed 2000 characters.',
        ];
    }
}
