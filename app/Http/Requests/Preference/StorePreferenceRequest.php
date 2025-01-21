<?php

namespace App\Http\Requests\Preference;

use Illuminate\Foundation\Http\FormRequest;

class StorePreferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_ids' => 'array',
            'category_ids.*' => 'integer',
            'author_ids' => 'array',
            'author_ids.*' => 'integer',
            'source_ids' => 'array',
            'source_ids.*' => 'integer',
        ];
    }

    protected function prepareForValidation() {
        if (!$this->has('category_ids') || !$this->input('category_ids')) {
            $this->merge(['category_ids' => []]);
        }

        if (!$this->has('author_ids') || !$this->input('author_ids')) {
            $this->merge(['author_ids' => []]);
        }

        if (!$this->has('source_ids') || !$this->input('source_ids')) {
            $this->merge(['source_ids' => []]);
        }
    }
}
