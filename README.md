ajouter le .env (tu passe du .env.example en .env)
modifie ça :
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=fast_learning
DB_USERNAME=..............
DB_PASSWORD=............

Lancement du projet : 
```bash 
sail artisan up -d
```

Migration 
```bash
sail artisan migrate 
```

ajouter les donnees dans la database : 
sail migrate db:seed


# Fast Learning

# Authentification : Sanctum

## les plus : 
- simple et léger
- support des applications SPA et API (authentification sans état )
- génère facilement les tokens
- chaque token a des permissions
- authentification multi-dispositifs (android, ios, web, etc.)
Dans mon cas, j'utiliserai une authentification utilisant des tokens API en raison de sa compatibilité avec les dispositifs mobiles. 
Utiliser des tokens API pour les applications mobiles garantit une expérience utilisateur fluide et sécurisée.
Les fonctionnalités SPA de Sanctum, basées sur les cookies et les protections CSRF, sont mieux adaptées aux applications web et SPA
