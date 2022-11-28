# Realisation technique du blog

Visual studio code-éditeur de texte (html/php/css) 
phpmyadmin: serveur de gestion de base de données 
Git/Github: Gestionnaire de versions


## L'initialisation du projet
Installez git et récupérez le projet de Github:
git@github.com:marinejourdan/PROJET8-Todolist.git

Paramêtrer le projet avec vos propres données (.env).
-exécutez les migrations (3 versions à éxecuter avec chargement du jeu de données dans les migrations)

composer.json
Au sein de votre composer.json à la racine du projet, vous aurez accès aux versions actuelles des bundles et services de votre projet.
Il vous faudra faire évoluer le projet à partir des mises à jour de ce composer.

console
Accès à la console au sein du dossier bin au sein du projet.

security.yml
Accès à la partie sécurité du projet : connexions et rôles définis.
 access_control:

 - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
 - { path: ^/logout, roles: ROLE_USER }
 - { path: ^/tasks/.*, roles: ROLE_USER }
 - { path: ^/tasks, roles: IS_AUTHENTICATED_ANONYMOUSLY }
 - { path: ^/users, roles: ROLE_ADMIN }
 role_hierarchy:
 ROLE_ADMIN: ROLE_USER

### LES POINTS PRINCIPAUX DU PROJET

- Cas utilisation :  Cas pratique  pour l'utilisateur du projet

- les diagrammes de séquences : échanges client/serveur et base de données

- modèles de données: modèle conceptuel et physique de données/ organisation des relations basées sur le modèle UML.

- config: Outils de configuration du projet (security)

- bin: console

- src: data fixtures- controller - entités- formulaires

- templates: pages d'affichage en twig

- var: cache-logs-sessions

- vendor: bundles

- public:css, font, images...

- tests: à effectuer après chaque modification

- migrations: à effectuer lors de l'initialisation du projet