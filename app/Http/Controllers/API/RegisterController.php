<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Register\LoginRequest;
use App\Http\Requests\Register\RegisterRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
    /** @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"Authentification"},
     *      summary="Permet de se créer un compte utilisateur",
     *      description="Permet de se créer un compte utilisateur. Il faut renseigner l'adresse mail et le username qui doit être des identifiants uniques, le mot de passe, le prénom et le nom",
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string",description="Adresse mail. A renseigner afin d'obtenir son token utilisateur (api_token (JWT))",example="johnnn@example.com"),
     *              @OA\Property(property="username", type="string",description="l'username",example="Jojon"),
     *              @OA\Property(property="first_name", type="string",description="Prénom de l'utilisateur",example="John"),
     *              @OA\Property(property="last_name", type="string",description="Nom de l'utilisateur",example="Doe"),
     *              @OA\Property(property="password", type="string",description="Mot de passe.",example="password"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created success",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 1;
        $input['status'] = "active";
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully.', 201);
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Authentification"},
     *      summary="Permet de se connecter à l’API fast-learning",
     *      description="Permet de se connecter à l’API.  Il faut renseigner une adresse mail/un mot de passe",
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *                @OA\Property(property="email", type="string",description="Adresse mail",example="john@example.com"),
     *                @OA\Property(property="password", type="string",description="Mot de passe",example="password")
     *           ),
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
     *           response=405,
     *           description="Method Not Allowed",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(
     *           response=422,
     *           description="Unprocessable Entity",
     *           @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        try {
            if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
                /** @var User $user */
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->first_name . " " . $user->last_name;
                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Email & Password does not match.', ['error' => 'Unauthorised'], 401);
            }
        } catch (\Throwable $th) {
            return $this->sendError('Error.', ['error' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout",
     *      tags={"Authentification"},
     *      summary="Permet de se déconnecter de l’API fast-learning",
     *      description="Permet de se déconnecter de l’API.",
     *      security={{ "sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->sendResponse([], 'User logout successfully.', 200);
    }
}
