<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /** @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"Authentification"},
     *      summary="Permet de se créer un compte utilisateur",
     *      description="Permet de se créer un compte utilisateur via une adresse mail/mot de passe/prénom/nom",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string",description="Adresse mail. A renseigner afin d'obtenir son token utilisateur (api_token (JWT))",example="johnnn@example.com"),
     *              @OA\Property(property="password", type="string",description="Mot de passe. A renseigner afin d'obtenir son token utilisateur (api_token (JWT))",example="password"),
     *              @OA\Property(property="c_password", type="string",description="Mot de passe. A renseigner afin d'obtenir son token utilisateur (api_token (JWT))",example="password"),
     *              @OA\Property(property="username", type="string",description="l'username",example="Jojon"),
     *              @OA\Property(property="first_name", type="string",description="Prénom de l'utilisateur",example="John"),
     *              @OA\Property(property="last_name", type="string",description="Nom de l'utilisateur",example="Doe"),
     *           ),
     *      ),
     *     @OA\Response(
     *           response=200,
     *           description="Le token est retourné en cas de succès de la connexion.",
     *           @OA\MediaType( mediaType="application/json" )
     *        ),
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => "required",
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 1;
        $input['status'] = "active";
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     * @OA\Post(
     *       path="/login",
     *       operationId="login",
     *       tags={"Authentification"},
     *       summary="Permet de se connecter à l’API fast-learning",
     *       description="Permet de se connecter à l’API.  via une adresse mail/mot de passe",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(property="email", type="string",description="Adresse mail. A renseigner afin d'obtenir son token utilisateur (api_token (JWT))",example="john@example.com"),
     *               @OA\Property(property="password", type="string",description="Mot de passe. A renseigner afin d'obtenir son token utilisateur (api_token (JWT))",example="password")
     *            ),
     *       ),
     *       @OA\Response(
     *          response=200,
     *          description="Le token est retourné en cas de succès de la connexion.",
     *          @OA\MediaType( mediaType="application/json" )
     *       ),
     *   )
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var User $user
             *
             */
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->first_name . " " . $user->last_name;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Logout api
     * @OA\Post(
     *       path="/logout",
     *       operationId="logout",
     *       tags={"Authentification"},
     *       summary="Permet de se déconnecter de l’API fast-learning",
     *       description="Permet de se déconnecter de l’API.  via une adresse mail/mot de passe",
     *       security={{ "sanctum": {} }},
     *       @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\MediaType( mediaType="application/json" )
     *       ),
     *   )
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'User logout successfully.');
    }
}
