<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Tag\TagStoreRequest;
use App\Http\Requests\Tag\TagUpdateRequest;
use App\Http\Resources\TagRessource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class TagController extends BaseController
{
    /** @OA\Get(
     *      path="/tags",
     *      summary="Permet de récupérer l'ensemble des tags",
     *      description="Permet de récupérer l'ensemble des tags",
     *      operationId="tags",
     *      tags={"Tag"},
     *      security={{ "sanctum": {} }},
     *      @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Parameter(
     *             description="afficher les cartes d'un tag",
     *             in="query",
     *             name="cards",
     *             required=false,
     *             example="false",
     *             @OA\Schema(
     *                 type="boolean"
     *             )
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
        $tags = Tag::all();
        return $this->sendResponse(TagRessource::collection($tags), 'tags retrieved successfully.');
    }

    /** @OA\Post (
     *      path="/tags",
     *      summary="création d'un tag",
     *      description="Store a newly created tag in storage.",
     *      operationId="initTag",
     *      tags={"Tag"},
     *      security={{ "sanctum": {} }},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *                @OA\Property(property="name", type="string",description="",example="Dossier1"),
     *                @OA\Property(property="type", type="string",description="",example="Type de tag"),
     *           ),
     *      ),
     *      @OA\Response(
     *           response=201,
     *           description="Created success",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
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
    public function store(TagStoreRequest $request)
    {
        try {
            Gate::authorize('create', Tag::class);
            $validatedData = $request->validated();
            $tag = Tag::create($validatedData);
            return $this->sendResponse(new TagRessource($tag), 'Folder created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Tag creation failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Get(
     *     path="/tags/{id}",
     *     summary="Permet de récupérer un tag",
     *     description="Permet de récupérer un tag",
     *     operationId="tag",
     *     tags={"Tag"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *          description="id du tag",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          description="afficher les cartes d'un tag",
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
            $tag = Tag::findOrFail($id);
            return $this->sendResponse(new TagRessource($tag), 'Tag retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Tag not found.',[$e->getMessage()], 404);
        }
    }

    /** @OA\Patch(
     *     path="/tags/{id}",
     *     summary="Permet de mettre à jour un tag",
     *     description="Permet de mettre à jour un tag",
     *     operationId="updateTag",
     *     tags={"Tag"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *              @OA\Property(property="name", type="string",description="",example="Dossier1"),
     *              @OA\Property(property="type", type="string",description="",example="Type de tag"),
     *           ),
     *      ),
     *      @OA\Parameter(
     *           description="id du tag",
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
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     *      @OA\Response(
     *           response=400,
     *           description="Validation Error",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\MediaType(mediaType="application/json")
     *       ),
     *      @OA\Response(
     *           response=404,
     *           description="Not Found",
     *           @OA\MediaType(mediaType="application/json")
     *       ),
     *      @OA\Response(
     *           response=422,
     *           description="Unprocessable Entity",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     */
    public function update(TagUpdateRequest $request, string $id)
    {
        try {
            Gate::authorize('update', Tag::class);
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $tag = Tag::findOrFail($id);
            $tag->fill($validatedData);
            $tag->save();
            return $this->sendResponse(new TagRessource($tag), 'Tag updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Tag not found.', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Tag update failed!', [$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/tags/{id}",
     *      summary="Permet de supprimer un tag",
     *      description="Permet de supprimer un tag",
     *      operationId="deleteTag",
     *      tags={"Tag"},
     *      security={{ "sanctum": {} }},
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
     *     @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\MediaType(mediaType="application/json")
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\MediaType(mediaType="application/json")
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not Found",
     *           @OA\MediaType(mediaType="application/json")
     *       ),
     *       @OA\Response(
     *            response=500,
     *            description="Internal Server Error",
     *            @OA\MediaType(mediaType="application/json")
     *       ),
     *      ),
     *  )
     */
    public function destroy(string $id)
    {
        try {
            Gate::authorize('delete', Tag::class);
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return $this->sendResponse(new TagRessource($tag), 'Tag deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Tag not found',[ $e->getMessage()], 404);
        }
    }
}
