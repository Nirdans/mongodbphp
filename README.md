# Application PHP pour MongoDB

## Introduction
Cette application démontre l'utilisation de MongoDB avec PHP, permettant d'effectuer des opérations CRUD (Create, Read, Update, Delete) et d'autres opérations avancées comme l'agrégation.

## Prérequis
- XAMPP installé sur le système
- Extension PHP MongoDB installée
- Serveur MongoDB fonctionnel

## Installation
1. **Installation de l'extension MongoDB pour PHP**
   ```bash
   sudo pecl install mongodb
   ```

2. **Activation de l'extension dans php.ini**
   Ajoutez la ligne suivante à votre fichier php.ini (/opt/lampp/etc/php.ini) :
   ```
   extension=mongodb.so
   ```

3. **Redémarrage de XAMPP**
   ```bash
   sudo /opt/lampp/lampp restart
   ```

4. **Installation des dépendances via Composer**
   ```bash
   cd /opt/lampp/htdocs/composant/mongodb
   composer require mongodb/mongodb
   ```

## Structure du projet
- **index.php** : Page principale de connexion à MongoDB
- **create.php** : Insertion de documents dans la collection
- **read.php** : Lecture et affichage des documents
- **update.php** : Mise à jour des documents existants
- **delete.php** : Suppression de documents
- **aggregate.php** : Requêtes d'agrégation
- **compare_collections.php** : Comparaison entre les collections users et test_users

## Description des fichiers

### index.php
Page principale qui vérifie la connexion à MongoDB et la disponibilité de l'extension PHP.

### create.php
Permet d'insérer des documents dans la collection test_users avec des identifiants et des noms.

### read.php
Affiche tous les documents de la collection et permet également de filtrer les résultats selon des critères spécifiques.

### update.php
Démontre différentes méthodes de mise à jour des documents, y compris la mise à jour d'un seul document, de plusieurs documents, et l'upsert.

### delete.php
Permet de supprimer des documents individuels ou multiples basés sur des critères de filtrage.

### aggregate.php
Exécute des requêtes d'agrégation pour obtenir des statistiques sur les données, comme le regroupement par première lettre du nom.

### compare_collections.php
Compare le contenu des collections users et test_users, en montrant un échantillon limité des données sensibles.

## Utilisation

1. Accédez à l'application via le navigateur web à l'adresse :
   ```
   http://localhost/composant/mongodb/index.php
   ```

2. Utilisez les différents fichiers pour effectuer diverses opérations sur la base de données :
   - Pour créer des données : `/create.php`
   - Pour lire des données : `/read.php`
   - Pour mettre à jour des données : `/update.php`
   - Pour supprimer des données : `/delete.php`
   - Pour des requêtes d'agrégation : `/aggregate.php`
   - Pour comparer les collections : `/compare_collections.php`

## Base de données et collection
Cette application utilise :
- Base de données : `Other`
- Collection : `test_users`

## Notes importantes
- La collection `users` contient des données sensibles et ne doit pas être modifiée directement.
- La collection `test_users` est utilisée pour les tests et démonstrations.
- Les documents dans `test_users` contiennent principalement les champs `id` et `Name`.

## Dépannage
Si vous rencontrez des erreurs concernant l'extension MongoDB, assurez-vous que :
1. L'extension est correctement installée via PECL
2. La ligne `extension=mongodb.so` est présente dans php.ini
3. XAMPP a été redémarré après les modifications
