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
     *       summary="Permet de récupérer l'ensemble des revoir",
     *       description="Permet de récupérer l'ensemble des revoir",
     *      operationId="reviews",
     *      tags={"Review"},
     *      security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Le review est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
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
     *      description="Store a newly created review in storage.",
     *      operationId="initReview",
     *      tags={"Review"},
     *      security={
     *           {"sanctum": {}},
     *       },
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="user_id", type="integer",description="",example=5),
     *              @OA\Property(property="card_id", type="integer",description="",example=1),
     *              @OA\Property(property="is_active", type="boolean",description="",example=true),
     *              @OA\Property(property="review_score", type="integer",description="",example=0),
     *              @OA\Property(property="review_date", type="string", format="date", description="The date of the review",example="2024-07-09"),
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
    public function store(ReviewStoreRequest $request)
    {
        try {
            $validatedData = $request->validated(); // Utilisation de validated pour récupérer les données validées
            $folder = Review::create($validatedData);
            return $this->sendResponse(new ReviewRessource($folder), 'Folder created successfully.', 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder creation failed!', 'error' => $e->getMessage()], 409);
        }
    }

    /** @OA\Get(
     *     path="/reviews/{id}",
     *     summary="Permet de récupérer une review",
     *     description="Permet de récupérer une review",
     *     operationId="review",
     *     tags={"Review"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Parameter(
     *            description="id d'une review",
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
    public function show(int $id)
    {
        try {
            $review = Review::findOrFail($id);
            return $this->sendResponse(new ReviewRessource($review), 'Review retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Review not found', (array)$e->getMessage(), 404);
        }
    }

    /** @OA\Patch(
     *     path="/reviews/{id}",
     *     summary="Permet de mettre à jour une review",
     *     description="Permet de mettre à jour une review",
     *     operationId="updateReview",
     *     tags={"Review"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *              @OA\Property(property="is_active", type="boolean",description="",example=true),
     *              @OA\Property(property="review_score", type="integer",description="",example=0),
     *              @OA\Property(property="review_date", type="string", format="date", description="The date of the review",example="2024-07-09"),
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
     *     @OA\Response(
     *           response=200,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder update failed!', 'error' => $e->getMessage()], 409);
        }
    }

    /**
     * @OA\Delete(
     *      path="/reviews/{id}",
     *      operationId="deleteReviews",
     *      tags={"Review"},
     *      summary="Permet de supprimer une review",
     *      description="Permet de supprimer une review",
     *      security={
     *            {"sanctum": {}},
     *        },
     *      @OA\Parameter(
     *             description="id de la review",
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
            $folder = Review::findOrFail($id);
            $folder->delete();
            return response()->json(['message' => 'Review deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Review not found', 'error' => $e->getMessage()], 404);
        }
    }
}
