<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\BaseFormRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class GetArticlesRequest extends BaseFormRequest
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
            'search' => 'sometimes|string|min:3|max:255',
            'category_id' => 'sometimes|integer',
            'source_id' => 'sometimes|integer',
            'published_at_from' => 'sometimes|date',
            'published_at_to' => 'sometimes|date',
            'page' => 'sometimes|integer|min:1',
            'page_size' => 'sometimes|integer|min:1',
        ];
    }

    protected function prepareForValidation() {
        if ($this->has('published_at_from')) {
            $startDate = Carbon::parse($this->input('published_at_from'))->startOfDay();
            $this->merge(['published_at_from' => $startDate]);
        }

        if ($this->has('published_at_to')) {
            $endDate = Carbon::parse($this->input('published_at_to'))->endOfDay();
            $this->merge(['published_at_to' => $endDate]);
        }
    }
}
