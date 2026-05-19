# Restaurant Vite&Gourmande#

# 🍽️ Restaurant Management System

Application web développée avec Symfony permettant de gérer un restaurant : menu, commandes, réservations et utilisateurs.
l'objetif du projet  est de moderniseerla visibilité de l'entreprise, et rend les échanges plus faciles avec les clients

# 🚀 Fonctionnalités

## visiteurs
- consultations de page d'accueil
- consultations de Menus
- galerie photos
- creation de compte
- connexion/deconnexion
- depots d'avis
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
## 🗄️ Base de données

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