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
    
    // Suppression d'un document
    $filter = ['id' => 4];
    
    $result = $collection->deleteOne($filter);
    
    echo "Nombre de documents supprimés: " . $result->getDeletedCount() . "<br>";
    
    // Suppression de plusieurs documents
    $filter = ['id' => ['$gt' => 3]];
    
    $resultMany = $collection->deleteMany($filter);
    
    echo "Nombre de documents supprimés: " . $resultMany->getDeletedCount() . "<br>";
    
    // Afficher les utilisateurs restants
    $remaining = $collection->find();
    
    echo "<h2>Utilisateurs restants après suppression</h2>";
    echo "<table border='1'>";
    echo "<tr><th>MongoDB ID</th><th>ID</th><th>Name</th></tr>";
    
    foreach ($remaining as $user) {
        echo "<tr>";
        echo "<td>" . $user->_id . "</td>";
        echo "<td>" . (isset($user->id) ? $user->id : 'N/A') . "</td>";
        echo "<td>" . (isset($user->Name) ? $user->Name : 'N/A') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
