<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('You must be an admin to view cards.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Card $card): Response
    {
        return $user->role === 'admin' || $this->isUserHasThisCard($user->id, $card) || $this->getFolderIsPublic($card)
            // Cette condition permet de restreindre seul les personnes ayant cette carte pour la voir

            ? Response::allow()
            : Response::deny("It is not your card. You cannot look at it");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $folder_id): Response
    {
        return $user->role === 'admin' || $this->isFolderCreatedByThisUser($user->id, $folder_id)
            ? Response::allow()
            : Response::deny('It is not your folder. You cannot create a card.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $folder_id, $request_folder_id): Response
    {
        if (! $this->isFolderCreatedByThisUser($user->id, $request_folder_id) && ! is_null($request_folder_id)) {
            return Response::deny('It is not your folder. You cannot move this card here.');
        }
        return $user->role === 'admin' || $this->isFolderCreatedByThisUser($user->id, $folder_id)
            ? Response::allow()
            : Response::deny('It is not your card. You cannot update it.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $folder_id): Response
    {
        return $user->role === 'admin' || $this->isFolderCreatedByThisUser($user->id, $folder_id)
            ? Response::allow()
            : Response::deny('It is not your card. You cannot delete it.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Card $card): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Card $card): bool
    {
        //
    }

    public function viewAnyByUser(User $user, User $requestUser)
    {
        return $user->id === $requestUser->id || $user->role === 'admin'
            ? Response::allow()
            : Response::deny('You are not authorized to view these cards.');

    }

    /* vérifie si la carte appartient bien à cet utilisateur */
    private function isUserHasThisCard($user_id, $card) : bool
    {
        return $card->reviews()->where('user_id', $user_id)->exists();
    }

    /* vérifie si le dossier est publique */
    private function getFolderIsPublic($card) : bool
    {
        return $card->folder->is_public ?? false;
    }

    /* vérifie si le dossier spécifié correspond bien au dossier créé par l'utilisateur */
    private function isFolderCreatedByThisUser ($user_id, $folder_id) : bool {
        return $user_id === \App\Models\Folder::where('id', $folder_id)->value('created_by_user');
    }
}
