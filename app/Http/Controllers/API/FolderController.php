<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Folder\FolderStoreRequest;
use App\Http\Requests\Folder\FolderUpdateRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FolderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /** @OA\Get(
     *      path="/folders",
     *       summary="Permet de récupérer l'ensemble des dossiers",
     *       description="Permet de récupérer l'ensemble des dossiers",
     *      operationId="folders",
     *      tags={"Dossier"},
     *      security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     * )
     */
    public function index()
    {
        $folders = Folder::all();
        return $this->sendResponse(FolderResource::collection($folders), 'folders retrieved successfully.');
    }

    /** @OA\Post (
     *      path="/folders",
     *      summary="création d'un dossier",
     *      description="Store a newly created folder in storage.",
     *      operationId="initFolder",
     *      tags={"Dossier"},
     *      security={
     *           {"sanctum": {}},
     *       },
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string",description="",example="Dossier1"),
     *              @OA\Property(property="content", type="string",description="",example="Content of the folder"),
     *              @OA\Property(property="is_public", type="boolean",description="",example=false),
     *              @OA\Property(property="parent_id", type="integer",description="",example=null),
     *              @OA\Property(property="created_by_user", type="integer",description="",example=1)
     *          ),
     *      ),
     *     @OA\Response(
     *           response=201,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *     ),
     *     @OA\Response(
     *             response=409,
     *             description="Folder creation failed!",
     *     ),
     *  )
     */
    public function store(FolderStoreRequest $request)
    {
        try {
            $validatedData = $request->validated(); // Utilisation de validated pour récupérer les données validées

            $folder = Folder::create($validatedData);

            return response()->json(['message' => 'Folder created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder creation failed!', 'error' => $e->getMessage()], 409);
        }
    }

    /** @OA\Get(
     *     path="/folders/{id}",
     *     summary="Permet de récupérer un dossier",
     *     description="Permet de récupérer un dossier",
     *     operationId="folder",
     *     tags={"Dossier"},
     *     security={
     *          {"sanctum": {}},
     *      },
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
     *     @OA\Response(
     *           response=200,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     */
    public function show(string $id)
    {
        $folder = Folder::find($id);
        return $folder;
    }

    /** @OA\Patch(
     *     path="/folders/{id}",
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
     *      summary="Permet de mettre à jour un dossier",
     *      description="Permet de mettre à jour un dossier",
     *     @OA\Response(
     *           response=200,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     */
    public function update(FolderUpdateRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $folder = Folder::findOrFail($id);
            $folder->fill($validatedData);
            $folder->save();
            return response()->json(['message' => 'Folder updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder update failed!', 'error' => $e->getMessage()], 409);
        }
    }

    /**
     * @OA\Delete(
     *      path="/folders/{id}",
     *      operationId="deleteFolder",
     *      tags={"Dossier"},
     *      summary="Permet de récupérer un dossier",
     *      description="Permet de récupérer un dossier",
     *      security={
     *            {"sanctum": {}},
     *        },
     *      @OA\Parameter(
     *             description="id du dossier",
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
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function destroy(string $id)
    {
        try {
            $folder = Folder::findOrFail($id);
            $folder->delete();
            return response()->json(['message' => 'Folder deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Folder not found', 'error' => $e->getMessage()], 404);
        }
    }
}
