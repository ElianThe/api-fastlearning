<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FolderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('You are not authorized to view folders.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Folder $folder): Response
    {
        return $user->role === 'admin' || (bool) $folder->is_public === true || $folder->created_by_user === $user->id
            ? Response::allow()
            : Response::deny('You are not authorized to view this folder.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Folder $folder): Response
    {
        return $user->role === 'admin' || $this->ownFolder($folder->parent_id, $user)
            ? Response::allow()
            : Response::deny('You are not authorized to create in this folder parent.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $folder, $validatedData): Response
    {
        // si le dossier appartient à l'utilisateur courant + si le dossier parent appartient à l'utilisateur courant
        return $user->role === 'admin' || $folder->created_by_user === $user->id && ! isset($validatedData['parent_id'])  || $folder->created_by_user === $user->id && $this->ownFolder($validatedData['parent_id'], $user)
            ? Response::allow()
            : Response::deny('You are not authorized to update in this folder parent.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Folder $folder): Response
    {
        return $user->role === 'admin' || $folder->created_by_user === $user->id
            ? Response::allow()
            : Response::deny('You are not authorized to delete this folder.');
    }


    private function ownFolder($folder_parent_id, $user)
    {
        if ($folder_parent_id === null) {
            return true;
        }
        $folder = Folder::findOrFail($folder_parent_id);
        return $folder->created_by_user === $user->id;
    }
}
