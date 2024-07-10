<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Card\CardStoreRequest;
use App\Http\Requests\Card\CardUpdateRequest;
use App\Http\Resources\CardRessource;
use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends BaseController
{
    /** @OA\Get(
     *      path="/cards",
     *      summary="Permet de récupérer l'ensemble des cartes.",
     *      description="Permet de récupérer l'ensemble des cartes.",
     *      operationId="cards",
     *      tags={"Carte"},
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
    public function index(Request $request) : JsonResponse
    {
        $cards = Card::all();
        return $this->sendResponse(CardRessource::collection($cards), 'cards retrieved successfully.');
    }

    /** @OA\Post (
     *      path="/cards",
     *      summary="création d'une carte",
     *      description="Création d'une carte",
     *      operationId="initCard",
     *      tags={"Carte"},
     *      security={{"sanctum": {}}},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *                @OA\Property(property="title", type="string",description="",example="Carte1"),
     *                @OA\Property(property="content", type="string",description="",example="Contenu de la carte"),
     *                @OA\Property(property="image_path", type="boolean",description="",example="/tmp/fakerFerad.jpg"),
     *                @OA\Property(property="folder_id", type="integer",description="",example=1),
     *           ),
     *      ),
     *      @OA\Response(
     *           response=201,
     *           description="Created success",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     *      @OA\Response(
     *            response=401,
     *            description="Unauthorized",
     *            @OA\MediaType(mediaType="application/json")
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
     *  )
     */
    public function store(CardStoreRequest $request) : JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $card = Card::create($validatedData);
            return $this->sendResponse(new CardRessource($card), 'Card created successfully.', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Card creation failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Get(
     *     path="/cards/{id}",
     *     summary="Permet de récupérer une carte.",
     *     description="Permet de récupérer une carte.",
     *     operationId="card",
     *     tags={"Carte"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *            description="id de la carte",
     *            in="path",
     *            name="id",
     *            required=true,
     *            example="1",
     *            @OA\Schema(
     *                type="integer"
     *            )
     *     ),
     *     @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\MediaType(mediaType="application/json")
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
    public function show(int $id) : JsonResponse
    {
        try {
            $card = Card::findOrFail($id);
            return $this->sendResponse(new CardRessource($card), 'Card retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', (array)$e->getMessage(), 404);
        }
    }

    /** @OA\Patch(
     *     path="/cards/{id}",
     *     summary="Permet de mettre à jour une carte.",
     *     description="Permet de mettre à jour une carte.",
     *     operationId="updateCard",
     *     tags={"Carte"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *               @OA\Property(property="title", type="string",description="",example="Nouveau Titre"),
     *               @OA\Property(property="content", type="string",description="",example="Contenu de la carte"),
     *               @OA\Property(property="image_path", type="boolean",description="",example="/tmp/fakerFB2HTD"),
     *               @OA\Property(property="folder_id", type="integer",description="",example=1),
     *           ),
     *     ),
     *     @OA\Parameter(
     *           description="id de la carte",
     *           in="path",
     *           name="id",
     *           required=true,
     *           example="1",
     *           @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *           response=400,
     *           description="Validation Error",
     *           @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *     @OA\Response(
     *           response=404,
     *           description="Not Found",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
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
     * )
     */
    public function update(CardUpdateRequest $request, int $id) : JsonResponse
    {
        try {
            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }
            $card = Card::findOrFail($id);
            $card->fill($validatedData);
            $card->save();
            return $this->sendResponse(new CardRessource($card), 'Card updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Card update failed!', [$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/cards/{id}",
     *      summary="Permet de supprimer une carte",
     *      description="Permet de supprimer un carte",
     *      operationId="deleteCard",
     *      tags={"Carte"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *             description="id du dossier",
     *             in="path",
     *             name="id",
     *             required=true,
     *             example="1",
     *             @OA\Schema(type="integer")
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=500,
     *           description="Internal Server Error",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *  )
     */
    public function destroy(int $id) : JsonResponse
    {
        try {
            $card = Card::findOrFail($id);
            $card->delete();
            return $this->sendResponse(new CardRessource($card), 'Card deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', (array)$e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Card deletion failed!', [$e->getMessage()], 500);
        }
    }

    /* récupère toutes les cartes d'un user */
    public function indexByUser(int $id) : JsonResponse
    {
        try {
            $user_with_cards = User::where('id', $id)
                ->with('cards')
                ->firstOrFail();

            // je récupère les cartes d'un user
            $cards = $user_with_cards->cards;
            return $this->sendResponse(CardRessource::collection($cards), 'Cards retrieved successfully.');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('User not found', (array)$exception->getMessage(), 404);
        }
    }

    /* récupère toutes les cartes qui sont à réviser */
    public function indexByUserAndReviews(int $id) : JsonResponse
    {
        try {
            $user_with_cards = User::where('id', $id)
                ->withWhereHas('cards', function ($query) {
                    $query->with('reviews')->where('review_date', '>', now());
                })
                ->firstOrFail();
            // je récupère les cartes d'un user qu'il doit réviser
            $cards = $user_with_cards->cards;
            return $this->sendResponse(CardRessource::collection($cards), 'Cards retrieved successfully.');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('User not found', (array)$exception->getMessage(), 404);
        }
    }
}
