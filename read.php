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
    
    // Récupérer tous les documents
    $allUsers = $collection->find();
    
    echo "<h2>Tous les utilisateurs</h2>";
    echo "<table border='1'>";
    echo "<tr><th>MongoDB ID</th><th>ID</th><th>Name</th></tr>";
    
    foreach ($allUsers as $user) {
        echo "<tr>";
        echo "<td>" . $user->_id . "</td>";
        echo "<td>" . (isset($user->id) ? $user->id : 'N/A') . "</td>";
        echo "<td>" . (isset($user->Name) ? $user->Name : 'N/A') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Recherche avec critères
    echo "<h2>Utilisateurs avec ID > 1</h2>";
    
    $filter = ['id' => ['$gt' => 1]];
    $options = [
        'sort' => ['id' => 1], // Tri par id croissant
    ];
    
    $filteredUsers = $collection->find($filter, $options);
    
    echo "<table border='1'>";
    echo "<tr><th>MongoDB ID</th><th>ID</th><th>Name</th></tr>";
    
    foreach ($filteredUsers as $user) {
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
