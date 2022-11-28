# LES POINTS PRINCIPAUX DU PROJET

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


## LES etapes de gestion du projet

Notre organisation de travail se déroule en étape, chaque mof=ification entraîne des points obligatoires pourpermettre une évolution securisée du projet et un travail en équipe.

1- Demande de modification du projet

2- réflexion et (re)écriture des tests fonctionnels en adéquation avec la modification à apporter (attention, certains tests fonctionnels seront à modifier si les fonctions évoluent)

3- modification à apporter dans la fonction

4- passage en  revue en ligne de commande des tests complets unitaires et fonctionnels

5- correction des éventuels bugs lors des tests

6- enregistrement de la couverture de code

7- Validation avec le chef de projet

Attention ! Chaque modification doit être suivie pas à pas sur github à l'aide des commits détaillant les étapes et d'une pull request accessible au chef de projet.

### Les tests unitaires et fonctionnels

Trouver les données des tests unitaires et fonctionnels dans le dossier tests.
Séparation en deux dossiers:

tests unitaires: concernent les tests effectués des entités et de leur retour répondant aux critères donnés.

tests fonctionnels: ces test concernent plus strictement les tests correspndant au retour des fonctions

Géérer un rapport de couverture après chaque modification du code (70% minimum de couverture).


#### L'authentification et le ROLE USER

Nous allons présenter ici la mise en place de l'authentification et des rôles au sein du projet: de l'enregistrement de l'utilisateur avec un rôle attribué aux accès suivant le rôle enregistré (ou anonymous).
Voici les rôles de chaque user et la manière dont ils sont organisés dans le fichier secutity.yml.

-  Anonyme
Ses accès:
liste des tâches
page login
Validation ou non d'une tâche

- Rôle user
Ses accès:
liste des tâches
page login
Validation ou non d'une tâche
création d'une tache
suppression  de ses propres tâches
modification d'une tâche

- Rôle admin
Ses accès:
liste des tâches
page login
Validation ou non d'une tâche
création d'une tache
suppression  de ses propres tâches
modification d'une tâche
gestion utilisateurs
(ajout/liste/modification)

Pour administrer vos utilisateurs, commencez par créer un utlisateur dans le formulaire et lui donner un rôle (rôle multiple possible). Le rôle est enregistré en base de données pour chaque utilisateur. Cette administration préalable est déjà implémentée lors de vos migrations.

Pour vérifier les accès de chaque type d'utilisateurs, se rendre dans le fichier security.yml /acces_control et configurer les paths(accès). Vous pouvez ajouter plusieurs rôles.
Ici, 3 rôles sont définis avec la configuration des accès définis ci-dessus.
Seul l'administrateur peut modifier cet accès pour un utilisateur

L'authentification est définie dans le secrity.yml/form_login: login_path: login
check_path: login_check
IL faudra donc se loguer pour être authentifié (sinon consideré comme anonymously).

##### Audit de code et de performance
ANALYSE SYMFONY INSIGHT

Analyse effectuée via github et l'outil d'analyse en ligne symfony insight.
Effectué durant les différentes évolutions du code pour correspondre aux normes symfony et permettre un partage du projet efficace avec un socle de normes communes

Utilisation de PHP CS-FIXER
PHP CS Fixer est un outil permettant de vérifier et corriger le formatage du code PHP selon le code style défini dans la configuration du projet.
Le style code est une convention qui définit comment doit être écrit le code. Par exemple l'emplacement des accolades des structures de contrôle, des classes, des fonctions, les espaces autour d'un signe égal, etc.
Cela permet de faciliter la lecture du code pour tout le monde.

Utilisation de symfony profiler

Audit de code vise à améliorer la qualité de l’application, augmenter ses performances, accélérer l’expérience utilisateur, augmenter la sécurité de l’application, identifier et anticiper les problèmes de sécurité et de performance, permettre au code d’être réutilisable pour tous selon les normes en vigueur.
vérifications à effectuer

le temps de rendu de la page;
la consommation de mémoire  ;
le nombre d'appels au cache ;
le temps de rendu des blocs Twig.

