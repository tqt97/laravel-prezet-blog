<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For simplicity, we'll allow anyone to create a post.
        // In a real application, you'd have authorization logic here.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $authorsConfig = config('prezet.authors');
        $authorKeys = array_keys($authorsConfig);

        return [
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'author' => 'required|string|in:'.implode(',', $authorKeys),
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
