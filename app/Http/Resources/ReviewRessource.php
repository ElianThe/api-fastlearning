<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewRessource extends JsonResource
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
            'user_id' => $this->user_id,
            'card_id' => $this->card_id,
            'is_active' => $this->is_active,
            'reviews_score' => $this->reviews_score,
            'review_date' => $this->review_date,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),

        ];

        if ($request->has('user') && $request['user'] === 'true') {
            $data['user'] = $this->user;
        }
        if ($request->has('card') && $request['card'] === 'true') {
            $data['card'] = $this->card;
        }

        return $data;
    }
}
