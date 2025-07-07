# calendrier_2025

## Description

Une application Symfony basée sur la version 7.2.2, utilisant les meilleures pratiques modernes avec PHP 8.2 et CSS.

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js avec npm
- Symfony CLI (facultatif, mais recommandé)

## Installation

1. Clonez le dépôt :
   ```bash
   git clone <https://github.com/ChronaMakenshi/calendrier_2025.git>
   cd calendrier_2025
   ```

2. Installez les dépendances PHP :
   ```bash
   composer install
   ```

3. Installez les dépendances JavaScript :
   ```bash
   npm install
   ```

4. Configurez l'application en copiant le fichier `.env` :
   ```bash
   cp .env .env.local
   ```
   Modifiez le fichier `.env.local` avec vos paramètres spécifiques (connexion à la base de données, etc.).

5. Exécutez les migrations de la base de données :
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

## Lancer l'application

1. Démarrez le serveur Symfony :
   ```bash
   symfony serve
   ```
   Ou utilisez le serveur PHP intégré :
   ```bash
   php -S localhost:8000 -t public/
   ```

3. Accédez à l'application via [http://localhost:8000](http://localhost:8000).

## Fonctionnalités principales

- Gestion intuitive du calendrier avec Symfony.
- Intégration des dernières fonctionnalités PHP 8.2.
- Styles modernes avec CSS et support de personnalisation.

## Contribution

1. Forkez le dépôt.
2. Créez une branche pour votre fonctionnalité/fix :
   ```bash
   git checkout -b ma-nouvelle-branche
   ```
3. Faites vos modifications et commitez :
   ```bash
   git commit -m "Ajout d'une nouvelle fonctionnalité"
   ```
4. Poussez vos changements :
   ```bash
   git push origin ma-nouvelle-branche
   ```
5. Ouvrez une Pull Request.


Une application Symfony basée sur la version 7.2.2, utilisant les meilleures pratiques modernes avec PHP 8.2 et CSS
