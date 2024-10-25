# API de Gestion de Prestataires

## Description

Cette application est une API de gestion de prestataires et de services, développée en Symfony 6.4, qui met en pratique les principes de l’architecture 12 Factor App pour assurer flexibilité, scalabilité, et résilience. Elle est destinée à une entreprise de services numériques souhaitant connecter prestataires et clients.

## Table des Matières

1. [Pré-requis](#pré-requis)
2. [Installation et Configuration](#installation-et-configuration)
3. [Endpoints de l'API](#endpoints-de-lapi)
4. [Principes 12 Factor App](#principes-12-factor-app)
5. [CI/CD avec GitHub Actions](#cicd-avec-github-actions)
6. [Documentation des Tests](#documentation-des-tests)
7. [Contributeurs](#contributeurs)

---

## Pré-requis

- **PHP** : Version 8.3 ou supérieure
- **Composer** : Gestionnaire de dépendances PHP
- **Symfony CLI** : Pour gérer le projet Symfony
- **Docker et Docker Compose** : Pour le déploiement et l'orchestration de l’application
- **Mailtrap** : Service de messagerie pour les notifications
- **Redis** : Pour le caching

---

## Installation et Configuration

1. **Cloner le projet** :
   ```bash
   git clone <lien-du-dépôt>
   cd FactorAppTD
   ```

2. **Installer les dépendances** :
   ```bash
   composer install
   ```

3. **Configurer les variables d’environnement** :
   - Renommer le fichier `.env.example` en `.env` et définir les valeurs appropriées pour les services externes.
   - Exemple de configuration dans `.env` :
     ```dotenv
     DATABASE_URL="mysql://db_user:db_password@db:3306/factorapp"
     REDIS_URL="redis://redis:6379"
     MAILER_DSN="smtp://username:password@smtp.mailtrap.io:2525"
     ```

4. **Mettre en place la base de données** :
   - Créer la base de données :
     ```bash
     php bin/console doctrine:database:create
     ```
   - Appliquer les migrations :
     ```bash
     php bin/console doctrine:migrations:migrate
     ```

5. **Démarrer l’application avec Docker** :
   - Construire et démarrer les services avec Docker Compose :
     ```bash
     docker-compose up -d
     ```
   - L'application sera accessible via `http://localhost:8000`.

---

## Endpoints de l'API

### Prestataires
- `GET /providers` : Récupérer la liste des prestataires
- `POST /providers` : Ajouter un nouveau prestataire
- `PUT /providers/{id}` : Modifier un prestataire
- `DELETE /providers/{id}` : Supprimer un prestataire

### Services
- `GET /services` : Récupérer la liste des services proposés
- `POST /services` : Ajouter un service
- `PUT /services/{id}` : Modifier un service
- `DELETE /services/{id}` : Supprimer un service

---

## Principes 12 Factor App

### 1. Codebase
   - Le projet est géré via Git avec une structure de branches pour faciliter les mises à jour et le déploiement.

### 2. Gestion des Dépendances
   - Toutes les dépendances sont définies dans `composer.json` et isolées dans le répertoire `vendor/`.

### 3. Configuration par Variables d’Environnement
   - La configuration, y compris les informations sensibles comme les identifiants de la base de données et les clés API, est gérée via le fichier `.env` et exclue du contrôle de version.

### 4. Backing Services
   - **MySQL** : Utilisé pour le stockage des prestataires et des services.
   - **Redis** : Utilisé pour le cache des requêtes fréquentes.
   - **Mailtrap** : Utilisé pour envoyer des notifications email lors de la création ou mise à jour d'un prestataire.

### 5. Build, Release, Run
   - **Build** : Gestion des dépendances en production avec `composer install --no-dev --optimize-autoloader`.
   - **Release** : Utilisation de tags Git pour versionner les nouvelles versions.
   - **Run** : L’application est lancée en mode production avec des optimisations de cache.

### 6. Logs
   - Monolog est configuré pour envoyer les logs vers stdout, permettant la capture en temps réel par Docker.

### 7. Processus Administratifs
   - Une commande `app:cleanup-db` est disponible pour automatiser le nettoyage de la base de données, et les migrations Doctrine sont également configurées pour être appliquées en production.

---

## CI/CD avec GitHub Actions

Un pipeline CI/CD est configuré pour effectuer les étapes suivantes :
1. **Build** : Installer les dépendances.
2. **Tests** : Exécuter les tests unitaires.
3. **Déploiement** : Mettre à jour la version en production avec `composer install --no-dev --optimize-autoloader`.

Exemple de configuration :

```yaml
name: Symfony CI/CD

on:
  push:
    branches:
      - main

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v2
      - name: Set up PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader
      - name: Run tests
        run: php bin/phpunit

  deploy:
    runs-on: ubuntu-latest
    needs: build
    steps:
      - name: Deploy to server
        run: |
          ssh user@server "cd /path/to/app && git pull origin main && composer install --no-dev --optimize-autoloader"
```

---