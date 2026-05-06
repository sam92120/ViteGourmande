# Restaurant Vite&Gourmande#

# 🍽️ Restaurant Management System

Application web développée avec Symfony permettant de gérer un restaurant : menu, commandes, réservations et utilisateurs.

## 🚀 Fonctionnalités

- Gestion des plats et catégories
- Réservations de tables
- Gestion des commandes
- Authentification des utilisateurs
- Espace administrateur et employes
- Tableau de bord de gestion

## 🛠️ Technologies utilisées

- PHP 8
- Symfony
- Doctrine ORM
- MySQL
- Twig
- Bootstrap

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

```text
http://127.0.0.1:8000
```

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