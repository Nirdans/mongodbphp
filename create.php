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
    
    // Données à insérer
    $document = [
        'id' => 1,
        'Name' => 'John Doe'
    ];
    
    // Insertion d'un document
    $result = $collection->insertOne($document);
    
    if ($result->getInsertedCount() > 0) {
        echo "Document inséré avec succès.<br>";
        echo "ID MongoDB: " . $result->getInsertedId();
    } else {
        echo "Échec de l'insertion du document.";
    }
    
    // Insertion de plusieurs documents
    $documents = [
        [
            'id' => 2,
            'Name' => 'Jane Smith'
        ],
        [
            'id' => 3,
            'Name' => 'Robert Johnson'
        ]
    ];
    
    $resultMany = $collection->insertMany($documents);
    
    echo "<br>Nombre de documents insérés: " . $resultMany->getInsertedCount();
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
