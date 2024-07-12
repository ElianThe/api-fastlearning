<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderStoreRequest extends FormRequest
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
            'name' => 'required|min:3|max:255|string',
            'content' => 'string|nullable',
            'is_public' => 'boolean|required',
            'parent_id' => 'numeric|nullable|exists:folders,id',
            'type' => 'string|nullable',
            'created_by_user' => 'prohibited',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_public' => $this->input('is_public') ?: false,
        ]);
    }
}
