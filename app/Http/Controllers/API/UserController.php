<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /** @OA\Get(
     *      path="/users",
     *       summary="Permet de récupérer l'ensemble des users",
     *       description="Permet de récupérer l'ensemble des users",
     *      operationId="users",
     *      tags={"User"},
     *      security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Les users est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     * )
     */
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), 'users retrieved successfully.');
    }

    /** @OA\Get(
     *     path="/users/{id}",
     *     summary="Permet de récupérer un user",
     *     description="Permet de récupérer un user",
     *     operationId="user",
     *     tags={"User"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Parameter(
     *            description="id du user",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *            @OA\Schema(
     *                type="integer"
     *            )
     *      ),
     *     @OA\Response(
     *           response=200,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found.');
        }
    }

    /** @OA\Patch(
     *     path="/users/{id}",
     *     operationId="updateUser",
     *     tags={"User"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *               @OA\Property(property="username", type="string",description="",example="UserName"),
     *               @OA\Property(property="first_name", type="boolean",description="",example="FirstName"),
     *               @OA\Property(property="last_name", type="string",description="",example="LastName"),
     *               @OA\Property(property="settings", type="string",description="",example={"background": "dark"}),
     *           ),
     *      ),
     *      @OA\Parameter(
     *            description="id du user",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *            @OA\Schema(
     *                type="integer"
     *            )
     *      ),
     *      summary="Permet de mettre à jour un user",
     *      description="Permet de mettre à jour un user",
     *     @OA\Response(
     *           response=200,
     *           description="Le user est retourné en cas de succès",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        try {
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $user = User::findOrFail($id);
            $user->fill($validatedData);
            $user->save();
            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred.');
        }
    }

    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Permet de récupérer un utilisateur",
     *      description="Permet de récupérer un utilisateur",
     *      security={
     *            {"sanctum": {}},
     *        },
     *      @OA\Parameter(
     *             description="id de l'utilisateur",
     *             in="path",
     *             name="id",
     *             required=true,
     *             example="1",
     *             @OA\Schema(
     *                 type="integer"
     *             )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="successful",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return $this->sendResponse([], 'User deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found.', ['error' => $e->getMessage()], 404);
        }
    }
}
