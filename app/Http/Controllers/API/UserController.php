<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class UserController extends BaseController
{
    /** @OA\Get(
     *      path="/users",
     *      summary="Permet de récupérer l'ensemble des users",
     *      description="Permet de récupérer l'ensemble des users",
     *      operationId="users",
     *      tags={"User"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="afficher les dossiers d'un utilisateur",
     *           in="query",
     *           name="folders",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="afficher les reviews d'un utilisateur",
     *           in="query",
     *           name="reviews",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="afficher les cards d'un utilisateur",
     *           in="query",
     *           name="cards",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     */
    public function index()
    {
        $users = User::all();
        Gate::authorize('viewAny', User::class);
        return $this->sendResponse(UserResource::collection($users), 'users retrieved successfully.');
    }

    /** @OA\Get(
     *     path="/users/{id}",
     *     summary="Permet de récupérer un user",
     *     description="Permet de récupérer un user",
     *     operationId="user",
     *     tags={"User"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *          description="id du user",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          description="afficher les dossiers d'un utilisateur",
     *          in="query",
     *          name="folders",
     *          required=false,
     *          example="false",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *     ),
     *     @OA\Parameter(
     *          description="afficher les reviews d'un utilisateur",
     *          in="query",
     *          name="reviews",
     *          required=false,
     *          example="false",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *     ),
     *     @OA\Parameter(
     *          description="afficher les cards d'un utilisateur",
     *          in="query",
     *          name="cards",
     *          required=false,
     *          example="false",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\MediaType(mediaType="application/json")
     *     ),
     * )
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            Gate::authorize('view', [User::class ,$user]);
            return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found.');
        }
    }

    /** @OA\Patch(
     *     path="/users/{id}",
     *     operationId="updateUser",
     *     tags={"User"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *               @OA\Property(property="username", type="string",description="",example="UserName"),
     *               @OA\Property(property="first_name", type="boolean",description="",example="FirstName"),
     *               @OA\Property(property="last_name", type="string",description="",example="LastName"),
     *               @OA\Property(property="settings", type="string",description="",example={"background": "dark"}),
     *          ),
     *     ),
     *     @OA\Parameter(
     *          description="id du user",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Created success",
     *          @OA\MediaType( mediaType="application/json" )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        try {
            $user = User::findOrFail($id);
            // vérifie si l'utilisateur connecté a le droit de modifier l'utilisateur
            Gate::authorize('update', [User::class ,$user]);

            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }

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
     *      summary="Permet de supprimer un utilisateur",
     *      description="Permet de supprimer un utilisateur",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="id de l'utilisateur",
     *           in="path",
     *           name="id",
     *           required=true,
     *           example="1",
     *           @OA\Schema(
     *               type="integer"
     *           )
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=404,
     *           description="Not Found",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *  )
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            Gate::authorize('delete', [User::class ,$user]);
            $user->delete();
            return $this->sendResponse([], 'User deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('User not found.', ['error' => $e->getMessage()], 404);
        }
    }
}
