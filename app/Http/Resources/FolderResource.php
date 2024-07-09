<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'is_public' => $this->is_public,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'created_by_user' => $this->created_by_user,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'links' => [
                'self' => route('folders.show', ['folder' => $this->id]),
            ],
        ];
    }
}
