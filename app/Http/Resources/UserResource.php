<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->role,
            'status' => $this->status,
            'settings' => $this->settings,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];

        if ($request->has('folders') && $request['folders'] === 'true') {
            $data['folders'] = $this->folders;
        }

        if ($request->has('reviews') && $request['reviews'] === 'true') {
            $data['reviews'] = $this->reviews;
        }

        if ($request->has('cards') && $request['cards'] === 'true') {
            $data['cards'] = $this->cards;
        }

        return $data;
    }
}
