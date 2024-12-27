# Africa Geo Junior

## Introduction
Africa Geo Junior est un site web destiné à améliorer les connaissances sur l'Afrique, ses pays, leurs villes et les informations principales.

## Objectifs du site web
Le site permet à l'utilisateur d'accéder à des informations sur les pays et leurs villes. Ces informations sont ajoutées, modifiées et supprimées par un administrateur.

## Modélisation des données
- **Diagramme de cas d’utilisation** : montre les principales fonctionnalités et les interactions des utilisateurs.
- **Diagramme de classes UML** : définit les entités, attributs, relations et méthodes du projet.

## Fonctionnalités principales
Le projet utilise PHP en Programmation Orientée Objet (POO) avec des classes et objets. Voici les principales fonctionnalités du site :

- **Utilisateur** :
  - Authentification
  - Affichage des pays et leurs informations
  - Affichage des villes associées à chaque pays

- **Administrateur** :
  - Authentification
  - Ajout, modification et suppression des pays et de leurs informations
  - Ajout, modification et suppression des villes associées aux pays

## Technologies et méthodes utilisées

- **Base de données** : MySQL pour la gestion des entités (Continents, Pays, Villes).
- **Frontend** : HTML et CSS pour la structure et la conception du site.
- **Backend** : PHP (POO) pour l'implémentation de la logique métier.
  - **PDO** : pour gérer les connexions et requêtes SQL de manière sécurisée.
