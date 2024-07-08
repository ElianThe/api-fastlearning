<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /** @OA\Get(
     *      path="/folders",
     *      operationId="folder",
     *      tags={"Dossier"},
     *     security={
     *          {"sanctum": {}},
     *      },
     *      summary="Permet de récupérer l'ensemble des dossiers",
     *      description="Permet de récupérer l'ensemble des dossiers",
     *     @OA\Response(
     *           response=200,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     */
    public function index()
    {
        $folders = Folder::all();
        return $this->sendResponse(FolderResource::collection($folders), 'folders retrieved successfully.');
    }

    /** @OA\Post (
     *      path="/folders",
     *      operationId="initFolder",
     *      tags={"Dossier"},
     *      summary="création d'un dossier",
     *      description="Permet de se créer un dossier via un nom de dossier et un id de dossier parent",
     *     security={
     *           {"sanctum": {}},
     *       },
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string",description="",example="Dossier1"),
     *              @OA\Property(property="content", type="string",description="",example="Content of the folder"),
     *              @OA\Property(property="is_public", type="boolean",description="",example=false),
     *              @OA\Property(property="created_by_user", type="integer",description="",example=1)
     *          ),
     *     ),
     *     @OA\Response(
     *           response=201,
     *           description="Le dossier est retourné en cas de succès de la connexion",
     *           @OA\MediaType( mediaType="application/json" )
     *     ),
     *     @OA\Response(
     *             response=401,
     *             description="Unauthenticated",
     *     ),
     * )
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $content = $request->input('content');
        $is_public = $request->input('is_public');
        $created_by_user = $request->input('created_by_user');
        try {
            $folder = new Folder();
            $folder->name = $name;
            $folder->content = $content;
            $folder->is_public = $is_public;
            $folder->created_by_user = $created_by_user;
            $folder->save();
            return response()->json(['message' => 'Folder created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Folder creation failed!'], 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
