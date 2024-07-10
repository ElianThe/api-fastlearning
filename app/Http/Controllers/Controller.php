<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API fast-learning",
 *      description="Bienvenue sur la version 1 de la documentation API de fast-learning.<br><br><u>Condition préalable :</u><br><li>Avoir un compte utilisateur</li><br><li>Une clé d'application <b>apiKey</b> vous sera donné lorsque que vous vous connectez/inscrivez. Utilisé là pour faire des requêtes</li><br>",
 *      @OA\Contact(
 *          name=": TODO AFTER THE SERVER IS ONLINE",
 *          email="elian.yutz@hotmail.fr"
 *      ),
 *      @OA\License(
 *          name="TODO AFTER THE SERVER IS ONLINE",
 *          url="https:google.com"
 *      ),
 * ),
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="apiKey",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * ),
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Serveur API Rest"
 * ),
 * @OA\Tag(
 *      name="Authentification",
 *      description="Connexion utilisateur à l'api."
 * ),
 * @OA\Tag(
 *      name="User",
 *      description="Gestion Utilisateur."
 * ),
 * @OA\Tag(
 *      name="Dossier",
 *      description="Gestion Dossier."
 * ),
 * @OA\Tag(
 *      name="Carte",
 *      description="Gestion Carte."
 * ),
 * @OA\Tag(
 *      name="Review",
 *      description="Gestion Review."
 * ),
 * @OA\Tag(
 *      name="Tag",
 *      description="Gestion Tag."
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
