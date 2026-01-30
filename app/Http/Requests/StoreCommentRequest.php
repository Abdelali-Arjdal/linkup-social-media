<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // All authenticated users can create comments
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Comment content is required.',
            'content.max' => 'Comment content cannot exceed 1000 characters.',
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
}
