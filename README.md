# 🍽️ Vite&Gourmande

## 📖 Présentation

**Vite&Gourmande** est une application web de gestion de restaurant développée avec **Symfony**.

L'application permet de gérer l'activité d'un restaurant : menus, plats, horaires, commandes, réservations, avis, utilisateurs, notifications et statistiques.

L'objectif du projet est de **moderniser la visibilité du restaurant** et de faciliter les échanges entre l'entreprise et ses clients.

---

# 🚀 Fonctionnalités

## 👥 Visiteurs

Les visiteurs peuvent :

* Consulter la page d'accueil
* Consulter les menus
* Consulter les plats
* Consulter la galerie photos
* Créer un compte
* Filtrer les menus
* Filtrer les plats
* Consulter les derniers avis

## 👤 Utilisateurs connectés

Les utilisateurs peuvent :

* Gérer leur compte
* Passer des commandes
* Consulter les menus
* Consulter leurs commandes
* Modifier une commande
* Annuler une commande

## 👨‍💼 Administrateurs

Les administrateurs peuvent :

* Gérer les utilisateurs
* Gérer les commandes
* Gérer et modifier les plats
* Gérer les menus
* Gérer les avis
* Gérer les employés
* Gérer les images
* Consulter les menus
* Consulter les statistiques
* Accéder au tableau de bord d'administration avec **EasyAdmin**

## 👷 Employés

Les employés peuvent :

* Gérer les commandes
* Gérer les menus
* Modifier ou annuler une commande à la demande du client

---

# 🧩 Technologies utilisées

* **Symfony 8.0.10**
* **PHP 8.5.10**
* **MySQL**
* **Doctrine ORM**
* **MongoDB Server 8.3.11**
* **Extension PHP MongoDB 2.5.3**
* **Doctrine MongoDB ODM**
* **MongoDB Compass**
* **EasyAdmin**
* **Chart.js**
* **Apache**
* **Heroku**
* **JawsDB**
* **MongoDB Atlas**
* **Git / GitHub**

---

# 🗄️ Architecture des bases de données

Le projet utilise une architecture hybride avec **MySQL** et **MongoDB**.

```text
                         Vite&Gourmande
                               │
                ┌──────────────┴──────────────┐
                │                             │
              MySQL                       MongoDB
                │                             │
        Données métier                Données d'activité
                │                             │
      ┌─────────┼─────────┐           restaurant_activity
      │         │         │                    │
   Utilisateurs Menus  Commandes       menu_view / plat_view
      │         │         │                    │
    Plats      Avis    Horaires           Statistiques
```

## 🗃️ MySQL — données métier

MySQL constitue la **source principale des données métier**.

Les principales données sont :

* Utilisateurs
* Rôles
* Menus
* Plats
* Commandes
* Avis
* Allergènes
* Régimes
* Thèmes
* Horaires
* Notifications
* Contacts

## 🍃 MongoDB — activité et statistiques

MongoDB est utilisé pour enregistrer les **données d'activité et de fréquentation** du site.

MongoDB ne contient pas de copie des menus ou des plats.

La collection principale est :

```text
restaurant_activity
```

Elle enregistre notamment :

* Les consultations de menus
* Les consultations de plats
* La date de consultation
* L'identifiant du menu
* L'identifiant du plat
* L'utilisateur éventuel
* Les métadonnées de navigation

Exemple :

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

1. Symfony récupère le menu depuis MySQL.
2. `RestaurantActivityService` enregistre l'activité dans MongoDB.
3. MongoDB conserve les informations de consultation.
4. Le dashboard EasyAdmin utilise MongoDB pour calculer les statistiques.
5. Les informations du menu sont récupérées depuis MySQL grâce à son identifiant.

### 📊 Statistiques

Le tableau de bord permet notamment d'afficher :

* Le nombre total de consultations de menus
* Le nombre total d'activités
* Les menus les plus consultés
* L'évolution des consultations sur les 7 derniers jours
* Le classement des menus les plus consultés

Les statistiques sont calculées à l'aide d'**agrégations MongoDB**.

---

# 🔐 Séparation des données

| MySQL         | MongoDB               |
| ------------- | --------------------- |
| Utilisateurs  | Activités             |
| Menus         | Consultations         |
| Plats         | Statistiques          |
| Commandes     | Dates de consultation |
| Avis          | Métadonnées           |
| Thèmes        | Types d'activité      |
| Horaires      | —                     |
| Notifications | —                     |

Cette séparation permet de conserver **MySQL comme source de vérité pour les données métier**, tout en utilisant MongoDB pour le suivi d'activité et les statistiques.

---

# 📦 Installation

## 1. Cloner le projet

```bash
git clone https://github.com/sam92120/ViteGourmande.git
cd ViteGourmande
```

## 2. Installer les dépendances

```bash
composer install
```

## 3. Configurer l'environnement

Créer un fichier `.env.local` à la racine du projet.

Exemple :

```dotenv
APP_ENV=dev
APP_DEBUG=1

DATABASE_URL="mysql://root:password@127.0.0.1:3306/vitegourmande"

MONGODB_URI="mongodb://localhost:27017"
MONGODB_DB="vitegourmande"
```

> ⚠️ Les valeurs de connexion doivent être adaptées à votre environnement local.
>
> Ne jamais publier de mot de passe, clé API ou autre secret dans Git.

## 4. Créer la base de données MySQL

```bash
php bin/console doctrine:database:create
```

## 5. Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

## 6. Configurer MongoDB

MongoDB doit être installé et démarré localement.

La connexion locale utilise :

```text
mongodb://localhost:27017
```

La base utilisée par l'application est :

```text
vitegourmande
```

Pour mettre à jour les index et la configuration du document MongoDB :

```bash
php bin/console doctrine:mongodb:schema:update --class="App\Document\RestaurantActivity"
```

## 7. Lancer le serveur Symfony

```bash
symfony server:start
```

L'application est alors accessible à :

```text
http://127.0.0.1:8000
```

---

# 🗄️ Structure SQL

La base de données relationnelle utilise **MySQL** avec **Doctrine ORM**.

Les principales entités sont :

```text
Utilisateur
Rôle
Menu
Plat
Commande
Avis
Allergène
Régime
Thème
```

### Relations principales

* Un utilisateur peut passer plusieurs commandes.
* Un utilisateur peut publier plusieurs avis.
* Un menu peut contenir plusieurs plats.
* Un plat peut être associé à plusieurs allergènes.

Les migrations Doctrine sont stockées dans :

```text
migrations/
```

---

# 🍃 Configuration MongoDB

Le document principal est :

```text
src/Document/RestaurantActivity.php
```

Le service chargé d'enregistrer et d'analyser les activités est :

```text
src/Service/RestaurantActivityService.php
```

La configuration Doctrine MongoDB se trouve dans :

```text
config/packages/doctrine_mongodb.yaml
```

---

# 🔐 Sécurité

L'application utilise le composant **Security de Symfony**.

Les principales mesures de sécurité sont :

* Authentification des utilisateurs
* Hashage sécurisé des mots de passe
* Gestion des rôles et des permissions
* Protection CSRF
* Validation des formulaires
* Séparation des variables sensibles de la configuration du code

Les secrets de production ne sont pas stockés dans Git.

---

# 📁 Structure du projet

```text
ViteGourmande/
│
├── config/
├── migrations/
├── public/
├── src/
│   ├── Controller/
│   ├── Document/
│   ├── Entity/
│   ├── Repository/
│   └── Service/
│
├── templates/
├── .env
├── composer.json
├── Procfile
└── README.md
```

---

# 🌿 Gestion Git

Le projet utilise une organisation Git basée sur plusieurs branches :

```text
main
develop
feature/*
```

Le développement est effectué principalement sur `develop`.

Les fonctionnalités spécifiques peuvent être développées dans des branches :

```text
feature/nom-de-la-fonctionnalite
```

---

# 📋 Gestion de projet

Le suivi du projet a été réalisé avec **Trello** afin de :

* Organiser les tâches
* Suivre leur avancement
* Prioriser les fonctionnalités
* Organiser les différentes étapes du développement

---

# 🚀 Déploiement en production

L'application est déployée sur **Heroku** avec une architecture hybride.

```text
                         Heroku
                           │
                    Symfony 8 / PHP 8.5
                           │
              ┌────────────┴────────────┐
              │                         │
           JawsDB                  MongoDB Atlas
              │                         │
           MySQL                   MongoDB
              │                         │
       Données métier          restaurant_activity
```

## ☁️ Hébergement

```text
Application : stark-coast-11185
Stack       : Heroku-24
PHP         : 8.5
Symfony     : 8.0
```

Le serveur web utilise Apache avec :

```text
web: heroku-php-apache2 public/
```

## 🗄️ MySQL en production

Les données métier sont stockées dans **MySQL via JawsDB**.

Symfony utilise la variable :

```text
DATABASE_URL
```

La valeur réelle de cette variable n'est pas stockée dans Git.

## 🍃 MongoDB en production

Les données d'activité sont stockées dans **MongoDB Atlas**.

Les variables utilisées sont :

```text
MONGODB_URI
MONGODB_DB
```

Les identifiants MongoDB ne sont jamais stockés dans le dépôt.

## 🔑 Variables d'environnement

Les variables de production sont configurées directement dans les **Config Vars Heroku**.

Par exemple :

```text
APP_ENV
APP_DEBUG
APP_SECRET
DATABASE_URL
DEFAULT_URI
MAILER_DSN
ALLMYSMS_DSN
MONGODB_URI
MONGODB_DB
```

Les valeurs sensibles ne doivent jamais être publiées dans le dépôt Git.

---

# 📦 Déploiement

Pour envoyer la branche `develop` vers l'application Heroku :

```powershell
git push heroku develop:main
```

Heroku installe automatiquement les dépendances avec Composer et déploie l'application.

Après chaque modification :

```text
Modification du code
        ↓
Test en local
        ↓
git add
        ↓
git commit
        ↓
git push heroku develop:main
        ↓
Vérification en production
```

---

# 🗃️ Import de la base MySQL

La base MySQL locale peut être exportée avec `mysqldump`.

Le fichier :

```text
vitegourmande.sql
```

contient les données de la base locale et doit rester ignoré par Git.

Il ne doit pas être publié lorsqu'il contient des données réelles ou sensibles.

La base peut ensuite être importée dans la base MySQL de production.

---

# 🧪 Vérification après déploiement

Après chaque déploiement, vérifier :

```text
✓ Application accessible
✓ Connexion MySQL fonctionnelle
✓ Données métier disponibles
✓ Connexion MongoDB fonctionnelle
✓ Dashboard EasyAdmin accessible
✓ Statistiques disponibles
✓ Images et assets accessibles
```

Pour consulter les logs Heroku :

```powershell
heroku logs --tail --app stark-coast-11185
```

Pour vérifier la connexion MongoDB en production :

```powershell
heroku run php bin/console doctrine:mongodb:schema:update -a stark-coast-11185
```

---

# 🛠️ Maintenance Heroku

### Consulter les variables de configuration

```powershell
heroku config --app stark-coast-11185
```

> ⚠️ Attention : certaines variables peuvent contenir des informations sensibles. Ne jamais publier leur valeur.

### Redémarrer l'application

```powershell
heroku restart --app stark-coast-11185
```

### Consulter les logs

```powershell
heroku logs --tail --app stark-coast-11185
```

---

# 🔒 Données sensibles

Les éléments suivants ne doivent jamais être commités :

* Mots de passe MySQL
* Mots de passe MongoDB
* Clés API
* Identifiants SMTP
* Tokens Heroku
* Fichiers `.env.local`
* Dumps SQL contenant des données réelles

Les secrets de production sont stockés dans les **Config Vars Heroku**.

---

# 👤 Compte de démonstration

Pour des raisons de sécurité, les identifiants réels d'un compte administrateur ne sont pas publiés dans ce README.

Pour tester l'administration, créer un compte de démonstration ou utiliser des identifiants fournis séparément.

---

# 📌 Résumé technique

| Élément               | Technologie          |
| --------------------- | -------------------- |
| Framework             | Symfony 8.0.10       |
| Langage               | PHP 8.5.10           |
| Base métier           | MySQL                |
| ORM                   | Doctrine ORM         |
| Base NoSQL            | MongoDB              |
| ODM                   | Doctrine MongoDB ODM |
| Administration        | EasyAdmin            |
| Graphiques            | Chart.js             |
| Base MySQL production | JawsDB               |
| MongoDB production    | MongoDB Atlas        |
| Serveur web           | Apache               |
| Hébergement           | Heroku               |
| Gestion du code       | Git / GitHub         |
| Gestion de projet     | Trello               |

---

# 👨‍💻 Auteur

Projet réalisé par **Samuel METELUS**.

# 📄 Licence

Projet éducatif développé avec Symfony.
