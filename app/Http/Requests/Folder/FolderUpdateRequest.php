<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderUpdateRequest extends FormRequest
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
            'name' => 'min:3|max:255|string',
            'content' => 'string',
            'is_public' => 'boolean',
            'parent_id' => 'numeric|exists:folders,id|nullable',
            'type' => 'string',
            'created_by_user' => 'prohibited',
        ];
    }

    public function messages()
    {
        return [
            "content.string" => "Le contenu doit être une chaîne de caractères",
            "is_public.boolean" => "Le champ is_public doit être un booléen",
            "parent_id.numeric" => "Le champ parent_id doit être un nombre",
            "parent_id.exists" => "Le champ parent_id doit exister dans la table folders",
            "type.string" => "Le champ type doit être une chaîne de caractères",
        ];
    }
}
