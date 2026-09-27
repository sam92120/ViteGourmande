# Restaurant Vite&Gourmande#

# 🍽️ Restaurant Management System

Application web développée avec SYMFONY permettant de gérer un restaurant : menu, plat, horaire, notification, commandes, réservations et utilisateurs.
l’objectif du projet  est de moderniser la visibilité de l'entreprise, et  rend les échanges plus faciles avec les clients

# 🚀 Fonctionnalités

## visiteurs
- consultations de page d'accueil
- consultations de Menus
- galerie photos
- creation de compte
- connexion/deconnexion
- depots d'avis
- reserver un menu
  
## utilisateurs connnectés
- gerer son compte
- passer  des commandes
- Tableau de bord de gestion
- consulter les menus ou les commandes

## adminitrateurs

- Gestion des utilisateurs
- Gestion des commandes 
- Gestion des avis
- tableaun de bord adminitrateur via EasyAdmin

# 🛠️ Technologies utilisées

- PHP 8
- Symfony
- Doctrine ORM
- MySQL
- Twig
- Bootstrap
- EasyAdmin
- Git/GitHub

## 📦 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/ton-compte/restaurant-project.git
```

### 2. Accéder au dossier

```bash
cd restaurant-project
```

### 3. Installer les dépendances

```bash
composer install
```

### 4. Configurer l’environnement


Créer un fichier `.env.local` :

```env
DATABASE_URL="mysql://root:password@127.0.0.1:3306/restaurant_db"
```

### 5. Créer la base de données


##  Base de donnée Nosql
## 🍃 MongoDB — Statistiques et suivi d'activité

Le projet **Vite&Gourmande** utilise une architecture hybride avec **MySQL** et **MongoDB**.

### 🎯 Rôle de MongoDB

MongoDB est utilisé uniquement pour stocker les **données d'activité et de fréquentation** du site.

Les données métier principales restent stockées dans MySQL :

* Utilisateurs
* Menus
* Plats
* Commandes
* Avis
* Thèmes
* etc.

MongoDB ne contient donc pas de copie des menus ou des plats.

### 🏗️ Architecture

```text
                    Vite&Gourmande
                          │
                ┌─────────┴─────────┐
                │                   │
             MySQL              MongoDB
                │                   │
        Données métier       Données d'activité
                │                   │
        ┌───────┼───────┐           │
        │       │       │           │
      Menus   Plats  Commandes      │
                                    │
                            restaurant_activity
                                    │
                         ┌──────────┴──────────┐
                         │                     │
                     menu_view             statistiques
```

### 📊 Données enregistrées

La collection MongoDB `restaurant_activity` enregistre notamment les consultations de menus.

Exemple de document :

```json
{
    "type": "menu_view",
    "menuId": 1,
    "platId": null,
    "userId": 1,
    "date": "2026-09-27T21:17:12.418Z",
    "metadata": {
        "source": "website",
        "page": "menu_show"
    }
}
```

### 🔄 Fonctionnement

Lorsqu'un utilisateur consulte un menu :

1. Symfony récupère le menu depuis **MySQL**.
2. `RestaurantActivityService` enregistre une activité dans **MongoDB**.
3. MongoDB conserve la date, le type d'activité, l'identifiant du menu et éventuellement l'utilisateur.
4. Le dashboard EasyAdmin utilise les données MongoDB pour calculer les statistiques.

Le nom du menu est ensuite récupéré depuis **MySQL** grâce à son identifiant.

### 📈 Statistiques disponibles

Le dashboard administrateur affiche notamment :

* Nombre total de consultations de menus
* Nombre total d'activités enregistrées
* Menus les plus consultés
* Évolution des consultations sur les 7 derniers jours
* Classement des menus les plus consultés

Les statistiques sont calculées avec des **agrégations MongoDB** afin de regrouper et compter les activités.

### ⚙️ Configuration

La connexion MongoDB est configurée dans `.env` :

```dotenv
MONGODB_URI=mongodb://localhost:27017
MONGODB_DB=ViteGourmande
```

Doctrine MongoDB ODM est utilisé pour connecter Symfony à MongoDB.

Le document principal est :

```text
src/Document/RestaurantActivity.php
```

Le service chargé d'enregistrer et d'analyser les activités est :

```text
src/Service/RestaurantActivityService.php
```

### 🧩 Technologies utilisées

* **Symfony 8**
* **Doctrine MongoDB ODM**
* **MongoDB 8**
* **MongoDB Compass**
* **MySQL**
* **EasyAdmin**
* **Chart.js**

### 🔐 Principe de séparation des données

Le projet applique une séparation entre les deux bases :

| MySQL        | MongoDB               |
| ------------ | --------------------- |
| Utilisateurs | Activités             |
| Menus        | Consultations         |
| Plats        | Statistiques          |
| Commandes    | Dates de consultation |
| Avis         | Métadonnées           |
| Thèmes       | Types d'activité      |

Cette architecture permet de conserver **MySQL comme source de vérité pour les données métier** tout en utilisant **MongoDB pour le suivi d'activité et les statistiques**, sans dupliquer les données relationnelles.

## 🗄️ Base de données sql

La base de données est développée avec MySQL et Doctrine ORM.

Elle contient plusieurs entités principales :

- Utilisateur
- Rôle
- Menu
- Plat
- Commande
- Avis
- Allergène
- Régime
- Thème

### Relations principales

- Un utilisateur peut passer plusieurs commandes.
- Un utilisateur peut publier plusieurs avis.
- Un menu contient plusieurs plats.
- Un plat peut contenir plusieurs allergènes.

```bash
php bin/console doctrine:database:create
```

### 6. Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate


```

### 7. Lancer le serveur Symfony

```bash
symfony server:start
```

## ▶️ Accès à l’application

Ouvrir dans le navigateur :
``text
http://127.0.0.1:8000

##  8 Sécurité

l'application utilise le composant security de symfony 

. Authentification sécuritée
. Hashage des mots de passe
. Gestion des roles utilisateurs
. Protection CSRF
. Validation  de Formulaire


## 9 Gestion de projet

le suivi du projet été realis& avec trelo afin d'organiser les taches et suivre l'avancement du developpement 


## 10 Stucture de Git

Le projet suit organisation Git basé sur plusieurs branches
. main
.develop
. Feature/*


## Compte Test

# Administrateur
```
Email: vitegourmandadmin.com
mdp:Sam@92120
## 📁 Structure du projet

```text
src/
templates/
public/
config/
migrations/
```

## 👨‍💻 Auteur

Projet réalisé par [Samuel METELUS].

## 📄 Licence

Projet éducatif développé avec Symfony.
