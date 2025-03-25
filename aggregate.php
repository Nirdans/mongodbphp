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
    
    // Compter le nombre total d'utilisateurs
    $pipeline = [
        [
            '$group' => [
                '_id' => null,
                'total_utilisateurs' => ['$sum' => 1],
                'ids' => ['$push' => '$id']
            ]
        ]
    ];
    
    $result = $collection->aggregate($pipeline);
    
    echo "<h2>Statistiques des utilisateurs</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nombre total d'utilisateurs</th><th>Liste des IDs</th></tr>";
    
    foreach ($result as $stats) {
        echo "<tr>";
        echo "<td>" . $stats->total_utilisateurs . "</td>";
        echo "<td>" . implode(', ', $stats->ids) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Regrouper par première lettre du nom
    $pipeline = [
        [
            '$project' => [
                'id' => 1,
                'Name' => 1,
                'firstLetter' => ['$substr' => ['$Name', 0, 1]]
            ]
        ],
        [
            '$group' => [
                '_id' => '$firstLetter',
                'count' => ['$sum' => 1],
                'utilisateurs' => ['$push' => ['id' => '$id', 'Name' => '$Name']]
            ]
        ],
        [
            '$sort' => ['_id' => 1]
        ]
    ];
    
    $result = $collection->aggregate($pipeline);
    
    echo "<h2>Groupement par première lettre du nom</h2>";
    
    foreach ($result as $group) {
        echo "<h3>Lettre: " . $group->_id . " (" . $group->count . " utilisateurs)</h3>";
        
        echo "<ul>";
        foreach ($group->utilisateurs as $user) {
            echo "<li>ID: " . $user->id . " - " . $user->Name . "</li>";
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
