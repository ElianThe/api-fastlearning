<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Review\ReviewDateRequest;
use App\Http\Requests\Review\ReviewStoreRequest;
use App\Http\Requests\Review\ReviewUpdateRequest;
use App\Http\Resources\ReviewRessource;
use App\Models\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('viewAny', $reviews->first());
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
            $review = new Review($validatedData);
            // vérifie que l'on peut créer une review
            Gate::authorize('create', $review);
            $review->user_id = auth()->user()->id;
            $review->review_score = 0;
            $review->review_date = null;
            $review->save();
            return $this->sendResponse(new ReviewRessource($review), 'Review created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Review creation failed!', [$e->getMessage()], 500);
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
            Gate::authorize('view', $review);
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
    public function update(ReviewUpdateRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            // vérifie que les données du body de la requete ne sont pas vides
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }

            // vérifie que l'on peut mettre à jour la review
            $review = Review::findOrFail($id);
            Gate::authorize('update', $review);

            // si l'utilisateur a choisi de ne pas activer la carte
            if (isset($validatedData['is_active']) && (bool)$validatedData['is_active'] === false) {
                $review->review_score = 0;
                $review->review_date = null;
            } else {
                $review->fill($validatedData);
            }
            $review->save();
            return $this->sendResponse(new ReviewRessource($review), 'Review updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Review not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Review update failed!', 'error' => $e->getMessage()], 500);
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
            Gate::authorize('delete', $folder);
            $folder->delete();
            return $this->sendResponse([], 'Review deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Review not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Review deletion failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Post (
     *      path="/updateDateReview",
     *      summary="met à jour la date à laquelle l'utilisateur doit revoir la carte",
     *      description="met à jour la date à laquelle l'utilisateur doit revoir la carte",
     *      operationId="updateDataReview",
     *      tags={"Review"},
     *      security={{ "sanctum": {} }},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *                @OA\Property(property="id", type="integer",description="",example=1),
     *                @OA\Property(property="score", type="integer",description="",example=0),
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
    public function updateDateReview(ReviewDateRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        $review_id = $validatedData['id'];
        $score = $validatedData['score'];
        $review = Review::where('id', $review_id)->first();
        Gate::authorize('update', $review);

        //permet d'avoir toujours un score au dessus ou égal à 0 ou quand le score= -2 (revoir)
        if ($review->review_score === 0 && $score === -1 || $score=== -2 ) {
            $new_score = 0;
        } else {
            $new_score = $review->review_score + $score;
        }
        $review->review_date = match ($new_score) {
            0 => now(),
            1 => now()->addHours(7),
            2 => now()->addDays(1),
            3 => now()->addDays(3),
            4 => now()->addDays(7),
            5 => now()->addDays(14),
            6 => now()->addDays(30),
            7 => now()->addDays(180),
            8 => now()->addYears(),
            9 => now()->addYears(5),
            default => now()->addYears(10),
        };
        $review->review_score = $new_score;
        $review->save();
        return $this->sendResponse(new ReviewRessource($review), 'Review updated successfully.');
    }
}
