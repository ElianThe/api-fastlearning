# API FAST LEARNING :

L’application “Fast learning” est une application qui permet d’apprendre du vocabulaire grâce au système de cartes-mémoire avec des espacements pour pouvoir retenir sur le long terme. Cela repose sur des concepts de science cognitive et de psychologie.


<a href="https://api-fast-learning.fr/api/documentation" target="_blank">API FAST LEARNING</a>

## About the project :

### Authentification : Sanctum
- simple et léger
- support des applications SPA et API (authentification sans état)
- génère facilement les tokens
- chaque token a des permissions
- authentification multi-dispositifs (android, ios, web, etc.)
Dans mon cas, j'utiliserai une authentification utilisant des tokens API en raison de sa compatibilité avec les dispositifs mobiles. 
Utiliser des tokens API pour les applications mobiles garantit une expérience utilisateur fluide et sécurisée.
Les fonctionnalités SPA de Sanctum, basées sur les cookies et les protections CSRF, sont mieux adaptées aux applications web et SPA
