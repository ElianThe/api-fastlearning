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
