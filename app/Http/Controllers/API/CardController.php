<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Card\CardStoreRequest;
use App\Http\Requests\Card\CardUpdateRequest;
use App\Http\Resources\CardRessource;
use App\Models\Card;
use App\Models\Folder;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardController extends BaseController
{

    /** @OA\Get(
     *      path="/cards",
     *      summary="Permet de récupérer l'ensemble des cartes.",
     *      description="Permet de récupérer l'ensemble des cartes.",
     *      operationId="cards",
     *      tags={"Carte"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="ajout des dossier avec les cartes",
     *           in="query",
     *           name="folder",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="ajout des tags avec les cartes",
     *           in="query",
     *           name="tags",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="ajout des reviews avec les cartes",
     *           in="query",
     *           name="reviews",
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
    public function index(Request $request) : JsonResponse
    {
        Gate::authorize('viewAny', Card::class);
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
            //valide les données de la requete
            $validatedData = $request->validated();

            // authorise ou non la création de cette carte
            Gate::authorize('create', [Card::class, $validatedData['folder_id']]);

            $image_path = Str::random(32). '.' . $request['image_path']->getClientOriginalExtension();
            Storage::disk('public')->put($image_path, file_get_contents($validatedData['image_path']->path()));

            // création de la carte
            $card = Card::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'image_path' => $image_path,
                'folder_id' => $validatedData['folder_id']
            ]);

            //création d'une review pour la carte
            Review::create([
                'user_id' => auth()->user()->id,
                'card_id' => $card->id,
                'is_active' => true,
                'review_date' => null,
                'review_score' => 0
            ]);
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
     *     @OA\Parameter(
     *            description="ajout des dossier avec les cartes",
     *            in="query",
     *            name="folder",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *       ),
     *       @OA\Parameter(
     *            description="ajout des tags avec les cartes",
     *            in="query",
     *            name="tags",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *       ),
     *       @OA\Parameter(
     *            description="ajout des reviews avec les cartes",
     *            in="query",
     *            name="reviews",
     *            required=false,
     *            example="false",
     *            @OA\Schema(
     *                type="boolean"
     *            )
     *       ),
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
            Gate::authorize('view', $card);
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
     *               @OA\Property(property="image_path", type="",description="",example="/tmp/fakerFB2HTD"),
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
            $card = Card::findOrFail($id);

            $validatedData = $request->validated();
            if (empty($validatedData) || count($validatedData) === 0) {
                return response()->json(['message' => 'No data provided or there is an error in the request'], 400);
            }

            // Si le dossier donné ne correspond pas à un dossier créé par l'utilisateur, il ne pourra pas modifier sa carte
            $request_folder_id = null;
            if (isset($validatedData['folder_id'])) {
                $request_folder_id = $validatedData['folder_id'];
            }

            if (isset($validatedData['image_path'])) {
                $image_path = Str::random(32). '.' . $request['image_path']->getClientOriginalExtension();
                Storage::disk('public')->put($image_path, file_get_contents($validatedData['image_path']->path()));
                $validatedData['image_path'] = $image_path;
            }

            Gate::authorize('update', [Card::class, $card->folder->id,  $request_folder_id]);

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
     *           description="id de la carte",
     *           in="path",
     *           name="id",
     *           required=true,
     *           example="1",
     *           @OA\Schema(type="integer")
     *       ),
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
    public function destroy(int $id) : JsonResponse
    {
        try {
            $card = Card::findOrFail($id);
            Gate::authorize('delete', [Card::class, $card->folder_id]);
            $card->delete();
            return $this->sendResponse(new CardRessource($card), 'Card deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Card not found', (array)$e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Card deletion failed!', [$e->getMessage()], 500);
        }
    }

    /** @OA\Get(
     *      path="/cards-of-user",
     *      summary="Permet de récupérer l'ensemble des cartes en fonction d'un utilisateur.",
     *      description="Permet de récupérer l'ensemble des cartes en fonction d'un utilisateur.",
     *      operationId="cardsByUser",
     *      tags={"Carte"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="ajout des dossier avec les cartes",
     *           in="query",
     *           name="folder",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="ajout des tags avec les cartes",
     *           in="query",
     *           name="tags",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *      ),
     *      @OA\Parameter(
     *           description="ajout des reviews avec les cartes",
     *           in="query",
     *           name="reviews",
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
    public function indexByUser() : JsonResponse
    {
        try {
            $user_with_cards = User::where('id', auth()->user()->id)
                ->with('cards')
                ->firstOrFail();

            Gate::authorize('viewAnyByUser', [Card::class, $user_with_cards]);
            // je récupère les cartes d'un user
            $cards = $user_with_cards->cards;
            return $this->sendResponse(CardRessource::collection($cards), 'Cards retrieved successfully.');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('User not found', (array)$exception->getMessage(), 404);
        }
    }

    /** @OA\Get(
     *      path="/cards-to-review",
     *      summary="Permet de récupérer l'ensemble des cartes à réviser en fonction d'un utilisateur.",
     *      description="Permet de récupérer l'ensemble des cartes à réviser en fonction d'un utilisateur. Les cartes sont affichés par ordre croissant de date de révision. ça veut dire que la première carte affiché à apprendre est celle qui date la plus.",
     *      operationId="cardsReviewsByUser",
     *      tags={"Carte"},
     *      security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *           description="ajout des dossier avec les cartes",
     *           in="query",
     *           name="folder",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *       ),
     *       @OA\Parameter(
     *           description="ajout des tags avec les cartes",
     *           in="query",
     *           name="tags",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *       ),
     *       @OA\Parameter(
     *           description="ajout des reviews avec les cartes",
     *           in="query",
     *           name="reviews",
     *           required=false,
     *           example="false",
     *           @OA\Schema(
     *               type="boolean"
     *           )
     *       ),
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
    public function indexByUserAndReviews() : JsonResponse
    {
        try {
            $user_with_cards = User::where('id', auth()->user()->id)
                ->withWhereHas('cards', function ($query) {
                    $query->with('reviews')
                        ->where('review_date', '<', now())
                        ->where('is_active', true)
                        ->orderBy('review_date', 'asc');
                })
                ->firstOrFail();
            Gate::authorize('viewAnyByUser', [Card::class, $user_with_cards]);
            // je récupère les cartes d'un utilisateur qu'il doit réviser
            $cards = $user_with_cards->cards;
            return $this->sendResponse(CardRessource::collection($cards), 'Cards retrieved successfully.');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('There is no review to learn or the user is not found', (array)$exception->getMessage(), 404);
        }
    }

    /** @OA\Get(
     *      path="/learn-new-cards",
     *      summary="Permet de récupérer l'ensemble des cartes à apprendre en fonction d'un utilisateur.",
     *      description="Permet de récupérer l'ensemble des cartes à apprendre en fonction d'un utilisateur.",
     *      operationId="cardsLearnByUser",
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
    public function learnNewCardsByUser()
    {
        try {
            $user_with_cards = User::where('id', auth()->user()->id)
                ->withWhereHas('cards', function ($query) {
                    $query->with('reviews')
                        ->where('review_date', null)
                        ->where('review_score', 0)
                        ->where('is_active', true);
                })
                ->firstOrFail();
            $cards = $user_with_cards->cards;
            return $this->sendResponse(CardRessource::collection($cards), 'Cards retrieved successfully.');
        }catch (ModelNotFoundException $exception) {
            return $this->sendError('There is no carte to learn or the user is not found', (array)$exception->getMessage(), 404);
        }
    }
    /** @OA\Get(
     *      path="/folders/{id}/cards",
     *      summary="Permet de récupérer l'ensemble des cartes d'un dossier",
     *      description="Permet de récupérer l'ensemble des cartes d'un dossier",
     *      operationId="cardsByFolder",
     *      tags={"Carte"},
     *      security={{ "sanctum": {} }},
     *      @OA\Parameter(
     *           description="id du dossier",
     *           in="path",
     *           name="id",
     *           required=true,
     *           example="1",
     *           @OA\Schema(
     *                type="integer"
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
    public function cardsOfFolder(int $id)
    {
        try {
            $folder = Folder::where('id', $id)
                ->firstOrFail();
            $cards = $folder->cards;
            return $this->sendResponse(CardRessource::collection($cards), 'folders retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Folder not found', [$e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Folder retrieval failed!', [$e->getMessage()], 500);
        }
    }
}
