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
            'image_path' => $this->image_path,
            'folder_id' => $this->folder_id,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
        if ($request->has('folder')) {
            $data['folder'] = $this->folder;
        }

        /* si on veut afficher les tags avec la carte */
        if ($request->has('tags')) {
            $data['tags'] = $this->tags;
        }

        /* Si on veut afficher les reviews avec la carte */
        if ($request->has('reviews')) {
            $data['reviews'] = $this->reviews;
        }



        return $data;
    }
}
