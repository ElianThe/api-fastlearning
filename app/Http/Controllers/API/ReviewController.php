<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Review\ReviewStoreRequest;
use App\Http\Resources\ReviewRessource;
use App\Models\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    /** @OA\Get(
     *      path="/reviews",
     *      summary="Permet de récupérer l'ensemble des reviews",
     *      description="Permet de récupérer l'ensemble des reviews",
     *      operationId="reviews",
     *      tags={"Review"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="afficher la carte d'une review",
     *           in="query",
     *           name="card",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="afficher le user d'une review",
     *           in="query",
     *           name="user",
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
        $reviews = Review::all();
        return $this->sendResponse(ReviewRessource::collection($reviews), 'reviews retrieved successfully.');
    }

    /** @OA\Post (
     *      path="/reviews",
     *      summary="création d'une review",
     *      description="création d'une review",
     *      operationId="initReview",
     *      tags={"Review"},
     *      security={{ "sanctum": {} }},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *                @OA\Property(property="user_id", type="integer",description="",example=5),
     *                @OA\Property(property="card_id", type="integer",description="",example=1),
     *                @OA\Property(property="is_active", type="boolean",description="",example=true),
     *                @OA\Property(property="review_score", type="integer",description="",example=0),
     *                @OA\Property(property="review_date", type="string", format="date", description="The date of the review",example="2024-07-09"),
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
     *      ),
     *
     *      @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *  )
     */
    public function store(ReviewStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $folder = Review::create($validatedData);
            return $this->sendResponse(new ReviewRessource($folder), 'Folder created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Folder creation failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Get(
     *     path="/reviews/{id}",
     *     summary="Permet de récupérer une review",
     *     description="Permet de récupérer une review",
     *     operationId="review",
     *     tags={"Review"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *            description="id d'une review",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *            @OA\Schema(
     *                type="integer"
     *            )
     *     ),
     *     @OA\Parameter(
     *            description="afficher la carte d'une review",
     *            in="query",
     *            name="card",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *     ),
     *     @OA\Parameter(
     *            description="afficher le user d'une review",
     *            in="query",
     *            name="user",
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
     *     ),
     *     @OA\Response(
     *            response=404,
     *            description="Not Found",
     *            @OA\MediaType(mediaType="application/json")
     *     ),
     * )
     */
    public function show(int $id)
    {
        try {
            $review = Review::findOrFail($id);
            return $this->sendResponse(new ReviewRessource($review), 'Review retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Review not found', [$e->getMessage()], 404);
        }
    }

    /** @OA\Patch(
     *     path="/reviews/{id}",
     *     summary="Permet de mettre à jour une review",
     *     description="Permet de mettre à jour une review",
     *     operationId="updateReview",
     *     tags={"Review"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="is_active", type="boolean",description="",example=true),
     *              @OA\Property(property="review_score", type="integer",description="",example=0),
     *              @OA\Property(property="review_date", type="string", format="date", description="The date of the review",example="2024-07-09"),
     *          ),
     *      ),
     *     @OA\Parameter(
     *          description="id du dossier",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType( mediaType="application/json" )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Validation Error",
     *          @OA\MediaType( mediaType="application/json" )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     *           @OA\MediaType(mediaType="application/json")
     *     ),
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $folder = Review::findOrFail($id);
            $folder->fill($validatedData);
            $folder->save();
            return $this->sendResponse(new ReviewRessource($folder), 'Folder updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Folder not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder update failed!', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/reviews/{id}",
     *      summary="Permet de supprimer une review",
     *      description="Permet de supprimer une review",
     *      operationId="deleteReviews",
     *      tags={"Review"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="id de la review",
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
            $folder = Review::findOrFail($id);
            $folder->delete();
            return $this->sendResponse([], 'Review deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Review not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Review deletion failed!', [$e->getMessage()], 500);
        }
    }
}
