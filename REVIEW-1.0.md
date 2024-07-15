## Review 1.0

Pour la fonction register dans App\Http\Controllers\API\RegisterController

- Ajouter un champ pour la confirmation du mot de passe (password_confirm) et ajouter la vérification des deux champs (password, password_confirm) au fichier RegisterRequest
- Les credentials doivent être encodés en base64 par le client avant d'être envoyés au serveur (tu peux le faire en utilisant la fonction `btoa` en javascript)
- Les credentials doivent être décodés en base64 par le serveur avant d'être chiffrés avec la fonction bcrypt

Idem pour la fonction login dans App\Http\Controllers\API\LoginController
