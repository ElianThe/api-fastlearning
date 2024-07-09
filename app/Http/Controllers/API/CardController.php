<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardRessource;
use App\Models\Card;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CardController extends BaseController
{
    /** @OA\Get(
     *      path="/cards",
     *       summary="Permet de récupérer l'ensemble des cartes",
     *       description="Permet de récupérer l'ensemble des cartes",
     *      operationId="cards",
     *      tags={"Carte"},
     *      security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="La carte est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *      ),
     * )
     */
    public function index()
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
     *      security={
     *           {"sanctum": {}},
     *       },
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string",description="",example="Carte1"),
     *              @OA\Property(property="content", type="string",description="",example="Contenu de la carte"),
     *              @OA\Property(property="image_path", type="boolean",description="",example="/tmp/fakerFerad.jpg"),
     *              @OA\Property(property="folder_id", type="integer",description="",example=1),
     *          ),
     *      ),
     *     @OA\Response(
     *           response=201,
     *           description="La carte est retournée en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *     ),
     *     @OA\Response(
     *             response=409,
     *             description="Card creation failed!",
     *     ),
     *  )
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validated();
            $card = Card::create($validatedData);
            return $this->sendResponse(new CardRessource($card), 'Card created successfully.', 201);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card creation failed!', (array)$e->getMessage(), 409);
        }
    }

    /** @OA\Get(
     *     path="/cards/{id}",
     *     summary="Permet de récupérer une carte",
     *     description="Permet de récupérer une carte",
     *     operationId="card",
     *     tags={"Carte"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *      @OA\Parameter(
     *            description="id de la carte",
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
            $card = Card::findOrFail($id);
            return $this->sendResponse(new CardRessource($card), 'Card retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', (array)$e->getMessage(), 404);
        }
    }

    /** @OA\Patch(
     *     path="/cards/{id}",
     *     summary="Permet de mettre à jour une carte",
     *     description="Permet de mettre à jour une carte",
     *     operationId="updateCard",
     *     tags={"Carte"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *     @OA\RequestBody(
     *           @OA\JsonContent(
     *               @OA\Property(property="title", type="string",description="",example="Nouveau Titre"),
     *               @OA\Property(property="content", type="string",description="",example="Contenu de la carte"),
     *               @OA\Property(property="image_path", type="boolean",description="",example="/tmp/fakerFB2HTD"),
     *               @OA\Property(property="folder_id", type="integer",description="",example=1),
     *           ),
     *     ),
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
     *           description="La carte est retournée en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
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
            $card = Card::findOrFail($id);
            $card->fill($validatedData);
            $card->save();
            return $this->sendResponse(new CardRessource($card), 'Card updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', (array)$e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Card update failed!', (array)$e->getMessage(), 404);
        }
    }

    /**
     * @OA\Delete(
     *      path="/cards/{id}",
     *      operationId="deleteCard",
     *      tags={"Carte"},
     *      summary="Permet de supprimer une carte",
     *      description="Permet de supprimer un dossier",
     *      security={
     *            {"sanctum": {}},
     *      },
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
     *              mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function destroy(string $id)
    {
        try {
            $card = Card::findOrFail($id);
            $card->delete();
            return $this->sendResponse(new CardRessource($card), 'Card deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', (array)$e->getMessage(), 404);
        }
    }
}
