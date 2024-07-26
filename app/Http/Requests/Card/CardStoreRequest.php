<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;

class CardStoreRequest extends FormRequest
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
            'title' => 'min:3|max:255|string',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,svg',
            'folder_id' => 'numeric|exists:folders,id',
        ];
    }
}
