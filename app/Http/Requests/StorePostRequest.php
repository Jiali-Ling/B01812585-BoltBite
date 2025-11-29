<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'status' => 'required|in:draft,published',
        ];
    }
}
