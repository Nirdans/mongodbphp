# Guide complet pour maîtriser les requêtes MongoDB et les indices

## Introduction

MongoDB est une base de données NoSQL orientée documents qui offre des performances élevées, une haute disponibilité et une scalabilité automatique. Ce tutoriel vous guidera à travers les concepts essentiels des requêtes MongoDB et l'optimisation des performances avec les indices.

## Table des matières

1. [Bases des requêtes MongoDB](#1-bases-des-requêtes-mongodb)
2. [Types de requêtes avancées](#2-types-de-requêtes-avancées)
3. [Les indices MongoDB](#3-les-indices-mongodb)
4. [Analyse des performances avec indices](#4-analyse-des-performances-avec-indices)
5. [Meilleures pratiques](#5-meilleures-pratiques)
6. [Exemples pratiques en PHP](#6-exemples-pratiques-en-php)

## 1. Bases des requêtes MongoDB

### 1.1 Requêtes de lecture (find)

La méthode `.find()` est la plus courante pour lire des données:

```javascript
// Syntaxe de base
db.collection.find(query, projection)
```

Exemples:
```javascript
// Tous les documents
db.users.find()

// Avec un filtre simple
db.users.find({ "age": 25 })

// Avec des opérateurs de comparaison
db.users.find({ "age": { $gt: 18, $lt: 30 } })

// Avec plusieurs conditions (AND implicite)
db.users.find({ "status": "actif", "age": { $gt: 18 } })

// Avec $or (OR explicite)
db.users.find({ $or: [{ "status": "actif" }, { "age": { $gt: 50 } }] })
```

### 1.2 Projection

La projection permet de spécifier les champs à inclure ou exclure:

```javascript
// Inclure uniquement les champs name et age
db.users.find({}, { name: 1, age: 1 })

// Exclure le champ _id
db.users.find({}, { _id: 0, name: 1, age: 1 })
```

### 1.3 Tri, limite et décalage

```javascript
// Tri ascendant par nom
db.users.find().sort({ name: 1 })

// Tri descendant par âge, puis ascendant par nom
db.users.find().sort({ age: -1, name: 1 })

// Limiter à 5 résultats
db.users.find().limit(5)

// Sauter les 10 premiers résultats (pagination)
db.users.find().skip(10).limit(10)
```

## 2. Types de requêtes avancées

### 2.1 Opérateurs d'agrégation

Le framework d'agrégation permet des opérations avancées:

```javascript
// Groupement et calcul de moyenne
db.users.aggregate([
  { $match: { age: { $gt: 18 } } },
  { $group: { _id: "$status", avgAge: { $avg: "$age" }, count: { $sum: 1 } } },
  { $sort: { avgAge: -1 } }
])
```

### 2.2 Requêtes géospatiales

MongoDB prend en charge les requêtes géospatiales:

```javascript
// Trouver les documents à moins de 5000 mètres d'un point
db.places.find({
  location: {
    $near: {
      $geometry: {
        type: "Point",
        coordinates: [-73.9667, 40.78]
      },
      $maxDistance: 5000
    }
  }
})
```

### 2.3. Recherche en texte intégral

```javascript
// Créer un index de texte
db.articles.createIndex({ content: "text" })

// Recherche de texte
db.articles.find({ $text: { $search: "MongoDB tutorial" } })
```

## 3. Les indices MongoDB

### 3.1 Pourquoi utiliser des indices?

Les indices sont des structures de données qui améliorent la vitesse des opérations de requête. Sans indices, MongoDB doit scanner tous les documents d'une collection pour sélectionner ceux qui correspondent à la requête. Les indices réduisent le nombre de documents à examiner.

### 3.2 Types d'indices

MongoDB propose plusieurs types d'indices:

1. **Indices simples**: sur un seul champ
   ```javascript
   db.users.createIndex({ name: 1 }) // 1 pour ordre croissant, -1 pour décroissant
   ```

2. **Indices composés**: sur plusieurs champs
   ```javascript
   db.users.createIndex({ status: 1, age: -1 })
   ```

3. **Indices multiclés**: pour les tableaux
   ```javascript
   db.products.createIndex({ tags: 1 })
   ```

4. **Indices géospatiaux**: pour les données géographiques
   ```javascript
   db.places.createIndex({ location: "2dsphere" })
   ```

5. **Indices de texte**: pour la recherche textuelle
   ```javascript
   db.articles.createIndex({ content: "text" })
   ```

6. **Indices hachés**: pour le partitionnement de données
   ```javascript
   db.users.createIndex({ _id: "hashed" })
   ```

### 3.3 Gestion des indices

```javascript
// Lister tous les indices d'une collection
db.users.getIndexes()

// Supprimer un index
db.users.dropIndex("name_1")

// Supprimer tous les indices (sauf _id)
db.users.dropIndexes()
```

## 4. Analyse des performances avec indices

### 4.1 L'outil explain()

La méthode explain() permet d'obtenir des informations sur l'exécution d'une requête:

```javascript
// Analyser le plan d'exécution
db.users.find({ age: { $gt: 30 } }).explain()

// Analyser avec plus de détails
db.users.find({ age: { $gt: 30 } }).explain("executionStats")
```

### 4.2 Interprétation des résultats d'explain()

Éléments clés à examiner:
- `winningPlan`: le plan choisi pour exécuter la requête
- `stage`: les étapes du plan
  - `COLLSCAN`: scan complet de la collection (mauvais)
  - `IXSCAN`: scan d'index (bon)
  - `FETCH`: récupération des documents
- `executionTimeMillis`: temps d'exécution
- `totalKeysExamined`: nombre de clés d'index examinées
- `totalDocsExamined`: nombre de documents examinés

### 4.3 Comparaison des performances

| Opération | Sans indice | Avec indice | Gain de performance |
|-----------|-------------|-------------|---------------------|
| Recherche par égalité exacte | O(n) | O(log n) | Excellent |
| Recherche par plage de valeurs | O(n) | O(log n) | Très bon |
| Tri | Mémoire et temps | Utilise l'index | Excellent |
| Requêtes complexes | Très lent | Variable | Variable |

## 5. Meilleures pratiques

### 5.1 Règles générales

1. **Ordre des champs dans les indices composés**:
   - Conditions d'égalité exacte d'abord
   - Plages de valeurs ensuite
   - Tri en dernier

2. **Limitez le nombre d'indices**:
   - Chaque indice a un coût en espace de stockage
   - Les indices ralentissent les opérations d'écriture
   - Maximum 5-7 indices par collection (recommandation)

3. **Couvrez vos requêtes**:
   - Utilisez des indices couvrants qui incluent tous les champs nécessaires

4. **Surveillez l'utilisation des indices**:
   ```javascript
   db.users.aggregate([
     { $indexStats: {} }
   ])
   ```

### 5.2 Erreurs courantes à éviter

1. **Trop d'indices**: surcharge d'écriture et gaspillage d'espace

2. **Indices non utilisés**: créer des indices que les requêtes n'utilisent pas

3. **Mauvais ordre des champs**: ordre inefficace dans les indices composés

4. **Négligence des requêtes fréquentes**: les requêtes les plus fréquentes doivent être indexées en priorité

5. **Requêtes non optimisées**: requêtes qui ne peuvent pas utiliser les indices existants

## 6. Exemples pratiques en PHP

### 6.1 Création et utilisation d'indices

```php
<?php
// Création d'un indice simple
$collection->createIndex(['Name' => 1]);

// Création d'un indice composé
$collection->createIndex(['categorie' => 1, 'score' => -1]);

// Requête utilisant l'indice
$result = $collection->find(['Name' => 'John Doe']);
?>
```

### 6.2 Analyse des performances

```php
<?php
function mesurer_temps($callback) {
    $debut = microtime(true);
    $resultat = $callback();
    $fin = microtime(true);
    return [
        'resultat' => $resultat,
        'temps' => round(($fin - $debut) * 1000, 2) // en ms
    ];
}

// Comparaison des performances avec/sans indice
$sans_indice = mesurer_temps(function() use ($collection) {
    return $collection->find(['score' => ['$gt' => 50]])->toArray();
});

// Création de l'indice
$collection->createIndex(['score' => 1]);

$avec_indice = mesurer_temps(function() use ($collection) {
    return $collection->find(['score' => ['$gt' => 50]])->toArray();
});

echo "Sans indice: " . $sans_indice['temps'] . " ms<br>";
echo "Avec indice: " . $avec_indice['temps'] . " ms<br>";
echo "Amélioration: " . round((1 - $avec_indice['temps']/$sans_indice['temps']) * 100) . "%";
?>
```

### 6.3 Analyser l'exécution d'une requête avec explain()

```php
<?php
$explainOptions = ['explain' => true];
$explainResult = $collection->find(['id' => 5], $explainOptions);

echo "<pre>";
print_r(json_decode(json_encode($explainResult), true));
echo "</pre>";
?>
```

## Conclusion

L'optimisation des requêtes MongoDB par l'utilisation judicieuse des indices est essentielle pour les applications hautes performances. En suivant les meilleures pratiques et en analysant régulièrement les performances avec les outils appropriés, vous pouvez obtenir des gains significatifs en vitesse et en efficacité.

N'oubliez pas que l'optimisation est un processus continu : les modèles d'accès aux données évoluent avec votre application, et vos stratégies d'indexation doivent être revues régulièrement.
