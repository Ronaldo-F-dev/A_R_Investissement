# Projet Symfony
## Description

Ce projet est une application Symfony qui simule quelques fonctionnalités d'un site d'investissement. Ce guide vous explique comment cloner le projet, le lancer localement avec n'importe quel serveur **PHP**, changer les variables d'environnement, et déployer l'application en ligne.

## Prérequis

Avant de commencer, assurez-vous d'avoir les outils suivants installés sur votre machine :

* [XAMPP](https://www.apachefriends.org/fr/index.html) (qui inclut PHP et MySQL)
* [Composer](https://getcomposer.org/) (pour gérer les dépendances PHP)
* [Git](https://git-scm.com/) (pour cloner le projet)
* [CLI Symfony](https://git-scm.com/) (pour cloner le projet)

## Installation

### 1. Cloner le projet

* git clone https://github.com/Ronaldo-F-dev/A_R_Investissement.git

### 2. Déplacez-vous dans le dossier où se trouve le projet
* cd A_R_Investissement

### 3. Installer les dépendances

* Ouvrez un terminal, accédez au répertoire du projet , puis installez les dépendances avec Composer :
* cd /path/to/A_R_Investissement composer install

### 4. Configurer le fichier .env

* changer la 27è ligne du fichier .env de cette manière

* DATABASE_URL="mysql://utilisateur_de_votre_base_de_données:@127.0.0.1:3306/nom_de_votre_base_de_donnees"
* La 18è ligne par : APP_ENV=prod
* Changer la valeur des lignes 40 à 46 du fichier .env


### 5. Configurer la base de données

* dans le terminal, taper php bin/console doctrine:database:create et ensuite php bin/console doctrine:make:migration et enfin php bin/console doctrine:migrations:migrate


### 6. Lancer le serveur avec XAMPP

Démarrez XAMPP, puis lancez Apache et MySQL.
Accédez à votre projet dans le navigateur à l'adresse suivante :

* http://localhost:8000


## Contribuer

Les contributions sont les bienvenues ! Merci de créer une issue ou de soumettre une pull request si vous souhaitez améliorer le projet.

## Licence

Ce projet est libre et gratuit. Mais je ne serai en aucun cas responsable d'une mauvaise utilisation de ce projet. C'est juste un projet de test !
Merci !