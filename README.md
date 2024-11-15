# API FAST LEARNING :

## L'api de Fast Learning alimente l'application mobile. Ci-dessous, voici le lien de la documentation de l'API :
<a href="https://api-fast-learning.fr/api/documentation" target="_blank">API FAST LEARNING</a>
![](public/screenSwaggerApi.jpg)

## Les instructions d'installation du projet :
- Cloner le projet
- Installer les dépendances : `composer install`
- Créer un fichier .env : `cp .env.example .env`
-  alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)' : pour créer un alias de sail
- 

## Les commandes utiles pour travailler 
- sail up -d : pour démarrer les conteneurs
- sail down : pour arrêter les conteneurs
- php artisan migrate : pour exécuter les migrations
- php artisan db:seed : pour exécuter les seeders
- php artisan l5-swagger:generate : pour générer la documentation de l'API
- sail artisan migrate:fresh --seed : pour exécuter les migrations et les seeders

## Un bug trouvé
Si tu trouves un bug dans le code ou dans l'installation de cette application, s'il te plait, contacte moi par mail à cette adresse : guiffaultelian@gmail.com. Merci pour ta contribution.
