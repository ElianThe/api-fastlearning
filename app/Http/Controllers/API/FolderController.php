<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Folder\FolderStoreRequest;
use App\Http\Requests\Folder\FolderUpdateRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class FolderController extends BaseController
{

    /** @OA\Get(
     *      path="/folders",
     *      summary="Permet de récupérer l'ensemble des dossiers",
     *      description="Permet de récupérer l'ensemble des dossiers",
     *      operationId="folders",
     *      tags={"Dossier"},
     *      security={{ "sanctum": {} }},
     *      @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *            @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Parameter(
     *            description="afficher les cartes du dossier",
     *            in="query",
     *            name="cards",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *      ),
     *      @OA\Parameter(
     *            description="afficher les users du dossier",
     *            in="query",
     *            name="users",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *      ),
     *      @OA\Response(
     *            response=401,
     *            description="Unauthorized",
     *            @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     */
    public function index()
    {
        $folders = Folder::all();
        Gate::authorize('viewAny', Folder::class);
        return $this->sendResponse(FolderResource::collection($folders), 'folders retrieved successfully.');
    }

    /** @OA\Post (
     *     path="/folders",
     *     summary="Création d'un dossier",
     *     description="Création d'un dossier.",
     *     operationId="initFolder",
     *     tags={"Dossier"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *            @OA\JsonContent(
     *                 @OA\Property(property="name", type="string",description="",example="Dossier1"),
     *                 @OA\Property(property="content", type="string",description="",example="Content of the folder"),
     *                 @OA\Property(property="is_public", type="boolean",description="",example=false),
     *                 @OA\Property(property="parent_id", type="integer",description="",example=null),
     *            ),
     *     ),
     *     @OA\Response(
     *            response=201,
     *            description="Created success",
     *            @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *            response=401,
     *            description="Unauthorized",
     *            @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *           response=422,
     *           description="Unprocessable Entity",
     *           @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     *           @OA\MediaType(mediaType="application/json")
     *     ),
     *  )
     */
    public function store(FolderStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $folder = new Folder($validatedData);
            $folder->created_by_user = auth()->user()->id;
            Gate::authorize('create', [Folder::class ,$folder]);
            $folder->save();
            $folder->users()->attach(auth()->user()->id);
            return $this->sendResponse(new FolderResource($folder), 'Folder created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Card creation failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Get(
     *     path="/folders/{id}",
     *     summary="Permet de récupérer un dossier",
     *     description="Permet de récupérer un dossier",
     *     operationId="folder",
     *     tags={"Dossier"},
     *     security={{"sanctum": {} }},
     *     @OA\Parameter(
     *            description="id du dossier",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *            @OA\Schema(
     *                type="integer"
     *            )
     *     ),
     *     @OA\Parameter(
     *            description="afficher les cartes du dossier",
     *            in="query",
     *            name="cards",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *     ),
     *     @OA\Parameter(
     *            description="afficher les users du dossier",
     *            in="query",
     *            name="users",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *     ),
     *     @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *            @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *            response=401,
     *            description="Unauthorized",
     *            @OA\MediaType(mediaType="application/json")
     *      ),
     *     @OA\Response(
     *            response=404,
     *            description="Not Found",
     *            @OA\MediaType(mediaType="application/json")
     *     ),
     * )
     */
    public function show(string $id)
    {
        try {
            $folder = Folder::findOrFail($id);
            Gate::authorize('view', $folder);
            return $this->sendResponse(new FolderResource($folder), 'Folder retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Folder not found', [$e->getMessage()], 404);
        }
    }

    /** @OA\Patch(
     *     path="/folders/{id}",
     *     summary="Permet de mettre à jour un dossier",
     *     description="Permet de mettre à jour un dossier",
     *     operationId="updateFolder",
     *     tags={"Dossier"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string",description="",example="Nouveau dossier 1"),
     *               @OA\Property(property="content", type="string",description="",example="Content of the folder"),
     *               @OA\Property(property="is_public", type="boolean",description="",example=false),
     *               @OA\Property(property="parent_id", type="integer",description="",example=null),
     *               @OA\Property(property="type", type="string",description="type",example="type"),
     *           ),
     *      ),
     *      @OA\Parameter(
     *            description="id du dossier",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *            @OA\Schema(
     *                type="integer"
     *            )
     *      ),
     *      @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *            @OA\MediaType( mediaType="application/json")
     *      ),
     *      @OA\Response(
     *            response=400,
     *            description="Validation Error",
     *            @OA\MediaType( mediaType="application/json")
     *      ),
     *      @OA\Response(
     *            response=401,
     *            description="Unauthorized",
     *            @OA\MediaType(mediaType="application/json")
     *       ),
     *      @OA\Response(
     *            response=404,
     *            description="Not Found",
     *            @OA\MediaType(mediaType="application/json")
     *       ),
     *      @OA\Response(
     *            response=422,
     *            description="Unprocessable Entity",
     *            @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Internal Server Error",
     *            @OA\MediaType(mediaType="application/json"),
     *      )
     * )
     */
    public function update(FolderUpdateRequest $request, int $id)
    {
        try {
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $folder = Folder::findOrFail($id);
            Gate::authorize('update', [Folder::class, $folder, $validatedData]);
            $folder->fill($validatedData);
            $folder->save();
            return $this->sendResponse(new FolderResource($folder), 'Folder updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Folder not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Folder update failed!', [$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/folders/{id}",
     *      operationId="deleteFolder",
     *      tags={"Dossier"},
     *      summary="Permet de supprimer un dossier",
     *      description="Permet de supprimer un dossier",
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="id du dossier",
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
     *     @OA\Response(
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
    public function destroy(int $id)
    {
        try {
            $folder = Folder::findOrFail($id);
            Gate::authorize('delete', $folder);
            $folder->delete();
            return $this->sendResponse(new FolderResource($folder), 'Folder deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Folder not found', (array)$e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Folder deletion failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Get(
     *      path="/folders-of-user",
     *      summary="Permet de récupérer l'ensemble des dossiers en fonction d'un utilisateur.",
     *      description="Permet de récupérer l'ensemble des dossiers en fonction d'un utilisateur.",
     *      operationId="foldersByUser",
     *      tags={"Dossier"},
     *      security={{ "sanctum": {} }},
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
    public function indexByUser()
    {
        try {
            $folders = User::where('id', auth()->user()->id)->firstOrFail()->folders;
            return $this->sendResponse(FolderResource::collection($folders), 'folders retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Folder retrieval failed!', [$e->getMessage()], 500);
        }
    }
}
