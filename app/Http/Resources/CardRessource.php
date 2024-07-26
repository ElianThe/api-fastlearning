<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => $this->image_url,
            'folder_id' => $this->folder_id,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
        if ($request->has('folder') && $request['folder'] === 'true') {
            $data['folder'] = $this->folder;
        }

        /* si on veut afficher les tags avec la carte */
        if ($request->has('tags') && $request['tags'] === 'true') {
            $data['tags'] = $this->tags;
        }

        /* Si on veut afficher les reviews avec la carte */
        if ($request->has('reviews') && $request['reviews'] === 'true') {
            $data['reviews'] = $this->reviews;
        }

        return $data;
    }
}
