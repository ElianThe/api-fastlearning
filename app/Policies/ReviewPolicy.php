<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('You are not authorized to view reviews.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Review $review): Response
    {
        return $user->role === 'admin' || $user->id === $review->user->id
            ? Response::allow()
                : Response::deny('You are not authorized to view this review.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $review): Response
    {
        return $user->role === 'admin' || $this->publicFolder($review->card_id) || $this->ownFolder($user, $review->card_id)
            ? Response::allow()
            : Response::deny('You are not authorized to create review for this card.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): Response
    {
        return $user->role === 'admin' || $review->user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not authorized to update review for this card.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('You are not authorized to delete review for this card.');
    }


    private function ownFolder(User $user, $card_id_review): bool
    {
        $card = Card::find($card_id_review);
        return $card->folder->created_by_user === $user->id;
    }

    private function publicFolder($card_id_review): bool
    {
        $card = Card::find($card_id_review);
        return $card->folder->is_public;
    }
}
