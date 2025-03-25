# Guide des requêtes MongoDB en PHP

Ce document est un guide de référence pour l'utilisation des requêtes MongoDB avec PHP, expliquant les différents opérateurs et leur syntaxe.

## Connexion à MongoDB avec PHP

Voici le code de base pour se connecter à MongoDB et sélectionner une collection:

```php
// Inclure l'autoloader Composer
require 'vendor/autoload.php';

// Établir la connexion au serveur MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");

// Sélectionner la base de données "Other"
$db = $client->Other;

// Sélectionner la collection "test_users"
$collection = $db->test_users;
```

Cette connexion sera utilisée dans tous les exemples de ce guide.

## Table des matières
1. [Opérateurs de comparaison](#1-opérateurs-de-comparaison)
2. [Opérateurs logiques](#2-opérateurs-logiques)
3. [Opérateurs d'élément](#3-opérateurs-d'élément)
4. [Opérateurs de tableau](#4-opérateurs-de-tableau)
5. [Opérateurs de projection](#5-opérateurs-de-projection)
6. [Opérations d'insertion](#6-opérations-dinsertion)
7. [Opérations de mise à jour](#7-opérations-de-mise-à-jour)
8. [Opérations de suppression](#8-opérations-de-suppression)
9. [Exemples de requêtes complètes](#9-exemples-de-requêtes-complètes)

## 1. Opérateurs de comparaison

| Opérateur | Description | Équivalent SQL | Exemple PHP |
|-----------|-------------|----------------|-------------|
| `$eq` | Égal à (=) | `WHERE champ = valeur` | `['champ' => valeur]` ou `['champ' => ['$eq' => valeur]]` |
| `$gt` | Supérieur à (>) | `WHERE champ > valeur` | `['champ' => ['$gt' => valeur]]` |
| `$gte` | Supérieur ou égal à (>=) | `WHERE champ >= valeur` | `['champ' => ['$gte' => valeur]]` |
| `$lt` | Inférieur à (<) | `WHERE champ < valeur` | `['champ' => ['$lt' => valeur]]` |
| `$lte` | Inférieur ou égal à (<=) | `WHERE champ <= valeur` | `['champ' => ['$lte' => valeur]]` |
| `$ne` | Non égal à (!=) | `WHERE champ != valeur` | `['champ' => ['$ne' => valeur]]` |
| `$in` | Dans un ensemble | `WHERE champ IN (a, b, c)` | `['champ' => ['$in' => [a, b, c]]]` |
| `$nin` | Pas dans un ensemble | `WHERE champ NOT IN (a, b, c)` | `['champ' => ['$nin' => [a, b, c]]]` |

**Exemples PHP :**
```php
// Tous les utilisateurs âgés de plus de 25 ans
$collection->find(['age' => ['$gt' => 25]]);

// Utilisateurs entre 18 et 30 ans (inclusif)
$collection->find([
    'age' => ['$gte' => 18, '$lte' => 30]
]);

// Utilisateurs avec id 1, 2 ou 3
$collection->find(['id' => ['$in' => [1, 2, 3]]]);
```

## 2. Opérateurs logiques

| Opérateur | Description | Équivalent SQL | Exemple PHP |
|-----------|-------------|----------------|-------------|
| `$and` | ET logique | `WHERE cond1 AND cond2` | `['$and' => [cond1, cond2]]` |
| `$or` | OU logique | `WHERE cond1 OR cond2` | `['$or' => [cond1, cond2]]` |
| `$not` | NON logique | `WHERE NOT cond` | `['champ' => ['$not' => expression]]` |
| `$nor` | NI...NI (NON...OU) | `WHERE NOT (cond1 OR cond2)` | `['$nor' => [cond1, cond2]]` |

**Exemples PHP :**
```php
// Utilisateurs âgés de plus de 30 ans OU avec statut "premium"
$collection->find([
    '$or' => [
        ['age' => ['$gt' => 30]],
        ['statut' => 'premium']
    ]
]);

// Utilisateurs dont l'âge n'est PAS entre 20 et 30
$collection->find([
    '$nor' => [
        ['age' => ['$gte' => 20, '$lte' => 30]]
    ]
]);

// Explicite AND (normalement vous n'avez pas besoin de $and)
$collection->find([
    '$and' => [
        ['statut' => 'actif'],
        ['age' => ['$gt' => 18]]
    ]
]);
```

## 3. Opérateurs d'élément

| Opérateur | Description | Exemple PHP |
|-----------|-------------|-------------|
| `$exists` | Vérifie si le champ existe | `['champ' => ['$exists' => true/false]]` |
| `$type` | Vérifie le type BSON du champ | `['champ' => ['$type' => 'string']]` |

**Exemples PHP :**
```php
// Utilisateurs avec le champ "telephone" défini
$collection->find(['telephone' => ['$exists' => true]]);

// Documents où "age" est de type numérique
$collection->find(['age' => ['$type' => 'int']]);
```

## 4. Opérateurs de tableau

| Opérateur | Description | Exemple PHP |
|-----------|-------------|-------------|
| `$all` | Correspond si le tableau contient tous les éléments | `['tableau' => ['$all' => [val1, val2]]]` |
| `$elemMatch` | Correspond si au moins un élément du tableau satisfait tous les critères | `['tableau' => ['$elemMatch' => critères]]` |
| `$size` | Correspond si le tableau a la taille spécifiée | `['tableau' => ['$size' => taille]]` |

**Exemples PHP :**
```php
// Documents avec des tags contenant à la fois "php" ET "mongodb"
$collection->find(['tags' => ['$all' => ['php', 'mongodb']]]);

// Documents avec au moins un commentaire d'un utilisateur de plus de 30 ans
$collection->find([
    'commentaires' => [
        '$elemMatch' => [
            'user_age' => ['$gt' => 30],
            'approved' => true
        ]
    ]
]);

// Documents avec exactement 3 tags
$collection->find(['tags' => ['$size' => 3]]);
```

## 5. Opérateurs de projection

| Opérateur | Description | Exemple PHP |
|-----------|-------------|-------------|
| `$` | Projette le premier élément d'un tableau qui correspond | `['array.$' => true]` |
| `$elemMatch` | Projette le premier élément du tableau qui correspond aux critères | `['array' => ['$elemMatch' => critères]]` |
| `$slice` | Projette un sous-ensemble d'un tableau | `['array' => ['$slice' => [skip, limit]]]` |

**Exemples PHP :**
```php
// Récupérer juste le premier commentaire qui satisfait le critère
$collection->find(
    ['commentaires.user_id' => 123],
    ['projection' => ['commentaires.$' => 1]]
);

// Récupérer les 3 premiers commentaires de chaque document
$collection->find(
    [],
    ['projection' => ['commentaires' => ['$slice' => 3]]]
);
```

## 6. Opérations d'insertion

### 6.1 Insérer un document unique

```php
// Insertion d'un seul document
$document = [
    'id' => 1,
    'Name' => 'John Doe',
    'age' => 28,
    'email' => 'john.doe@example.com',
    'date_creation' => new MongoDB\BSON\UTCDateTime()
];

$result = $collection->insertOne($document);

// Vérifier le résultat
if ($result->getInsertedCount() > 0) {
    echo "Document inséré avec succès. ID: " . $result->getInsertedId();
} else {
    echo "Échec de l'insertion";
}
```

### 6.2 Insérer plusieurs documents

```php
// Insertion de plusieurs documents à la fois
$documents = [
    [
        'id' => 2,
        'Name' => 'Jane Smith',
        'age' => 35,
        'email' => 'jane.smith@example.com'
    ],
    [
        'id' => 3,
        'Name' => 'Robert Johnson',
        'age' => 42,
        'email' => 'robert.johnson@example.com'
    ]
];

$result = $collection->insertMany($documents);

echo "Nombre de documents insérés: " . $result->getInsertedCount();
echo "IDs insérés: ";
foreach ($result->getInsertedIds() as $id) {
    echo $id . ", ";
}
```

### 6.3 Insertion ordonnée et non ordonnée

```php
// Par défaut, insertMany s'arrête à la première erreur (ordonnée)
// Avec ordered: false, il continue même en cas d'erreur
$result = $collection->insertMany($documents, ['ordered' => false]);
```

## 7. Opérations de mise à jour

### 7.1 Opérateurs de mise à jour

| Opérateur | Description | Exemple |
|-----------|-------------|---------|
| `$set` | Définit la valeur d'un champ | `['$set' => ['field' => value]]` |
| `$unset` | Supprime un champ | `['$unset' => ['field' => '']]` |
| `$inc` | Incrémente la valeur d'un champ | `['$inc' => ['field' => amount]]` |
| `$mul` | Multiplie la valeur d'un champ | `['$mul' => ['field' => number]]` |
| `$rename` | Renomme un champ | `['$rename' => ['old_name' => 'new_name']]` |
| `$push` | Ajoute un élément à un tableau | `['$push' => ['array' => value]]` |
| `$pull` | Supprime des éléments d'un tableau | `['$pull' => ['array' => value]]` |
| `$addToSet` | Ajoute un élément à un tableau (sans doublon) | `['$addToSet' => ['array' => value]]` |
| `$currentDate` | Définit un champ à la date actuelle | `['$currentDate' => ['field' => true]]` |

### 7.2 Mettre à jour un seul document

```php
// Mettre à jour un document
$filter = ['id' => 1];
$update = [
    '$set' => [
        'Name' => 'John Doe Updated',
        'age' => 29
    ],
    '$currentDate' => ['derniere_modification' => true]
];

$result = $collection->updateOne($filter, $update);

echo "Documents trouvés: " . $result->getMatchedCount();
echo "Documents modifiés: " . $result->getModifiedCount();
```

### 7.3 Mettre à jour plusieurs documents

```php
// Mettre à jour plusieurs documents
$filter = ['age' => ['$lt' => 40]];
$update = [
    '$set' => ['categorie' => 'jeune'],
    '$inc' => ['score' => 5]
];

$result = $collection->updateMany($filter, $update);

echo "Documents trouvés: " . $result->getMatchedCount();
echo "Documents modifiés: " . $result->getModifiedCount();
```

### 7.4 Upsert (mise à jour ou insertion)

```php
// Upsert - insère si le document n'existe pas
$filter = ['id' => 4]; // Document inexistant
$update = [
    '$set' => [
        'id' => 4,
        'Name' => 'New User',
        'age' => 25
    ]
];
$options = ['upsert' => true];

$result = $collection->updateOne($filter, $update, $options);

if ($result->getUpsertedCount()) {
    echo "Document inséré avec ID: " . $result->getUpsertedId();
} else {
    echo "Document mis à jour";
}
```

### 7.5 Remplacer un document

```php
// Remplacer complètement un document
$filter = ['id' => 2];
$replacement = [
    'id' => 2,
    'Name' => 'Jane Smith Replaced',
    'profile' => 'Completely new document'
    // Note: tous les autres champs sont supprimés
];

$result = $collection->replaceOne($filter, $replacement);

echo "Documents modifiés: " . $result->getModifiedCount();
```

## 8. Opérations de suppression

### 8.1 Supprimer un document

```php
// Supprimer un seul document
$filter = ['id' => 1];

$result = $collection->deleteOne($filter);

echo "Documents supprimés: " . $result->getDeletedCount();
```

### 8.2 Supprimer plusieurs documents

```php
// Supprimer plusieurs documents
$filter = ['age' => ['$lt' => 30]];

$result = $collection->deleteMany($filter);

echo "Documents supprimés: " . $result->getDeletedCount();
```

### 8.3 Supprimer tous les documents d'une collection

```php
// Supprimer tous les documents (attention!)
$result = $collection->deleteMany([]);

echo "Tous les documents ont été supprimés: " . $result->getDeletedCount();
```

### 8.4 findOneAndDelete

```php
// Supprimer un document et le récupérer
$filter = ['id' => 3];
$options = [
    'sort' => ['id' => 1]
];

$deletedDoc = $collection->findOneAndDelete($filter, $options);

echo "Document supprimé: ";
print_r($deletedDoc);
```

## 9. Exemples de requêtes complètes

### Exemple 1: Filtrage multi-critères avec tri

```php
// Rechercher des utilisateurs actifs âgés de 25 à 40 ans, triés par âge décroissant
$utilisateurs = $collection->find(
    [
        'statut' => 'actif',
        'age' => ['$gte' => 25, '$lte' => 40]
    ],
    [
        'sort' => ['age' => -1],
        'limit' => 10
    ]
);
```

### Exemple 2: Requête avec conditions complexes

```php
// Utilisateurs premium qui ont plus de 100 points 
// OU utilisateurs standards qui ont plus de 500 points
$utilisateurs = $collection->find([
    '$or' => [
        [
            'type' => 'premium',
            'points' => ['$gt' => 100]
        ],
        [
            'type' => 'standard',
            'points' => ['$gt' => 500]
        ]
    ]
]);
```

### Exemple 3: Mettre à jour avec opérateurs

```php
// Incrémenter le score de 10 points et ajouter un tag
$collection->updateOne(
    ['id' => 12345],
    [
        '$inc' => ['score' => 10],
        '$push' => ['tags' => 'nouveau_tag'],
        '$currentDate' => ['derniere_modification' => true]
    ]
);
```

### Exemple 4: Agrégation pour calculer des statistiques

```php
$pipeline = [
    [
        '$match' => [
            'statut' => 'actif',
            'date_creation' => [
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime("-30 days") * 1000)
            ]
        ]
    ],
    [
        '$group' => [
            '_id' => '$region',
            'nombre_utilisateurs' => ['$sum' => 1],
            'age_moyen' => ['$avg' => '$age'],
            'score_total' => ['$sum' => '$score']
        ]
    ],
    [
        '$sort' => ['nombre_utilisateurs' => -1]
    ]
];

$resultats = $collection->aggregate($pipeline);
```

## Notes importantes

1. **Syntaxe PHP vs Shell MongoDB** : 
   - En shell MongoDB: `{champ: {$gt: valeur}}`
   - En PHP: `['champ' => ['$gt' => valeur]]`

2. **Types de données spécifiques à MongoDB** :
   - Dates: `new MongoDB\BSON\UTCDateTime(milliseconds)`
   - ObjectID: `new MongoDB\BSON\ObjectId("id_string")`
   - Decimal128: `new MongoDB\BSON\Decimal128("123.45")`

3. **Escape des caractères spéciaux** :
   - Les noms de champs contenant des caractères spéciaux ($, .) doivent être échappés

4. **Performance** :
   - Utilisez `explain()` pour analyser les requêtes
   - Créez des index pour les champs fréquemment interrogés
   - Minimisez la taille des requêtes et des résultats en utilisant la projection
