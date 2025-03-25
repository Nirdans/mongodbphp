<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'extension MongoDB est installée
if (!extension_loaded('mongodb')) {
    die("L'extension MongoDB pour PHP n'est pas installée.");
}

require 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->Other;
    $collection = $db->test_users;
    
    // Mise à jour d'un document
    $filter = ['id' => 1]; 
    $update = [
        '$set' => [
            'Name' => 'John Doe Updateds'
        ]
    ];
    
    $result = $collection->updateOne($filter, $update);
    
    echo "Nombre de documents correspondants: " . $result->getMatchedCount() . "<br>";
    echo "Nombre de documents modifiés: " . $result->getModifiedCount() . "<br>";
    
    // Mise à jour de plusieurs documents
    $filter = ['id' => ['$gt' => 1]];
    $update = [
        '$set' => [
            'statut' => 'actif'
        ]
    ];
    
    $resultMany = $collection->updateMany($filter, $update);
    
    echo "Nombre de documents correspondants: " . $resultMany->getMatchedCount() . "<br>";
    echo "Nombre de documents modifiés: " . $resultMany->getModifiedCount() . "<br>";
    
    // Upsert (insertion si non existant)
   /*  $filter = ['id' => 4];
    $update = [
        '$set' => [
            'id' => 4,
            'Name' => 'Nouvel Utilisateur'
        ]
    ];
    $options = ['upsert' => true];
    
    $resultUpsert = $collection->updateOne($filter, $update, $options);
    
    echo "Nombre de documents correspondants: " . $resultUpsert->getMatchedCount() . "<br>";
    echo "Nombre de documents modifiés: " . $resultUpsert->getModifiedCount() . "<br>";
    if ($resultUpsert->getUpsertedCount()) {
        echo "Document inséré avec ID MongoDB: " . $resultUpsert->getUpsertedId() . "<br>";
    } */
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
