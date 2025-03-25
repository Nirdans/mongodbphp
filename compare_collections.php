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
    
    // Référence aux deux collections
    $usersCollection = $db->users;
    $testUsersCollection = $db->test_users;
    
    // Nombre de documents dans chaque collection
    $usersCount = $usersCollection->countDocuments();
    $testUsersCount = $testUsersCollection->countDocuments();
    
    echo "<h2>Comparaison des collections</h2>";
    echo "<p>Nombre de documents dans 'users': " . $usersCount . "</p>";
    echo "<p>Nombre de documents dans 'test_users': " . $testUsersCount . "</p>";
    
    echo "<h3>Échantillon de documents 'users' (données sensibles - aperçu limité)</h3>";
    echo "<table border='1'>";
    echo "<tr><th>MongoDB ID</th><th>ID</th><th>Name</th></tr>";
    
    // Limiter l'affichage des données sensibles à seulement quelques documents
    $users = $usersCollection->find([], ['limit' => 2]);
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user->_id . "</td>";
        echo "<td>" . (isset($user->id) ? $user->id : 'N/A') . "</td>";
        echo "<td>" . (isset($user->Name) ? $user->Name : 'N/A') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<p><em>Note: Affichage limité des données sensibles.</em></p>";
    
    echo "<h3>Tous les documents 'test_users' (données de test)</h3>";
    echo "<table border='1'>";
    echo "<tr><th>MongoDB ID</th><th>ID</th><th>Name</th></tr>";
    
    $testUsers = $testUsersCollection->find();
    
    foreach ($testUsers as $user) {
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
