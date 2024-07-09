<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Tag\TagStoreRequest;
use App\Http\Requests\Tag\TagUpdateRequest;
use App\Http\Resources\TagRessource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagController extends BaseController
{
    /** @OA\Get(
     *      path="/tags",
     *      summary="Permet de récupérer l'ensemble des tags",
     *      description="Permet de récupérer l'ensemble des tags",
     *      operationId="tags",
     *      tags={"Tag"},
     *      security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Le tag est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     * )
     */
    public function index()
    {
        $tags = Tag::all();
        return $this->sendResponse(TagRessource::collection($tags), 'tags retrieved successfully.');
    }

    /** @OA\Post (
     *      path="/tags",
     *      summary="création d'un tag",
     *      description="Store a newly created tag in storage.",
     *      operationId="initTag",
     *      tags={"Tag"},
     *      security={
     *           {"sanctum": {}},
     *       },
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string",description="",example="Dossier1"),
     *              @OA\Property(property="type", type="string",description="",example="Type de tag"),
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
    public function store(TagStoreRequest $request)
    {
        try {
            $validatedData = $request->validated(); // Utilisation de validated pour récupérer les données validées

            $tag = Tag::create($validatedData);
            return $this->sendResponse(new TagRessource($tag), 'Folder created successfully.', 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder creation failed!', 'error' => $e->getMessage()], 409);
        }
    }

    /** @OA\Get(
     *     path="/tags/{id}",
     *     summary="Permet de récupérer un tag",
     *     description="Permet de récupérer un tag",
     *     operationId="tag",
     *     tags={"Tag"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Parameter(
     *            description="id du tag",
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
            $tag = Tag::findOrFail($id);
            return $this->sendResponse(new TagRessource($tag), 'Tag retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Tag not found!'], 404);
        }
    }

    /** @OA\Patch(
     *     path="/tags/{id}",
     *     summary="Permet de mettre à jour un tag",
     *     description="Permet de mettre à jour un tag",
     *     operationId="updateTag",
     *     tags={"Tag"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *              @OA\Property(property="name", type="string",description="",example="Dossier1"),
     * *            @OA\Property(property="type", type="string",description="",example="Type de tag"),
     *           ),
     *      ),
     *      @OA\Parameter(
     *            description="id du tag",
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
     *           description="Le tag est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     */
    public function update(TagUpdateRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $tag = Tag::findOrFail($id);
            $tag->fill($validatedData);
            $tag->save();
            return $this->sendResponse(new TagRessource($tag), 'Tag updated successfully.');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tag update failed!', 'error' => $e->getMessage()], 409);
        }
    }

    /**
     * @OA\Delete(
     *      path="/tags/{id}",
     *      operationId="deleteTag",
     *      tags={"Tag"},
     *      summary="Permet de supprimer un tag",
     *      description="Permet de supprimer un tag",
     *      security={
     *            {"sanctum": {}},
     *        },
     *      @OA\Parameter(
     *             description="id du tag",
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
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return $this->sendResponse(new TagRessource($tag), 'Tag deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Tag not found', (array)$e->getMessage(), 404);
        }
    }
}
