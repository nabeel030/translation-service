<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $translationId = $this->route('translation');

        return [
            'key' => 'required|string|max:255|unique:translations,key,' . ($translationId ? $translationId : ''),
            'tag' => 'required|string|max:255',
            'content' => 'required|array',
            'content.*.locale' => 'required|string|max:10',
            'content.*.content' => 'required|string',
        ];
    }
}
