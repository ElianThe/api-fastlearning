# API FAST LEARNING :

## L'api de Fast Learning alimente l'application mobile. Ci-dessous, voici le lien de la documentation de l'API :
<a href="https://api-fast-learning.fr/api/documentation" target="_blank">API FAST LEARNING</a>
[![Swagger Documentation](public/screenSwaggerApi.jpg)](https://api-fast-learning.fr/api/documentation)

## Les technologies utilisées 
- Php avec Laravel
- l'ORM Eloquent
- Utilisation de Sail pour lancer l'application sous docker 
- Swagger pour documenter l'api

## Les prerequis 
- avoir php avec les extensions nécessaires
- avoir composer 

## Les instructions d'installation du projet :
- Cloner le projet
- Installer les dépendances : `composer install` `npm install`
- Créer un fichier .env : `cp .env.example .env`
- Générer une clé d'application : `php artisan key:generate` 
- Installer Laravel Sail : `php artisan sail:install`
- Créer un alias pour Sail : `alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'`
- Lancer le docker : `sail up -d`
- Migrer la base de données : `sail artisan migrate`

## Les commandes utiles pour travailler 
- sail up -d : pour démarrer les conteneurs
- sail down : pour arrêter les conteneurs
- php artisan migrate : pour exécuter les migrations
- php artisan db:seed : pour exécuter les seeders
- php artisan l5-swagger:generate : pour générer la documentation de l'API
- sail artisan migrate:fresh --seed : pour exécuter les migrations et les seeders

## Un bug trouvé
Si tu trouves un bug dans le code ou dans l'installation de cette application, s'il te plait, contacte moi par mail à cette adresse : guiffaultelian@gmail.com. Merci pour ta contribution.
