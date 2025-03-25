<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'extension MongoDB est installée
if (!extension_loaded('mongodb')) {
    die("L'extension MongoDB pour PHP n'est pas installée. Veuillez l'installer avec:<br>
         <code>sudo pecl install mongodb</code><br>
         Puis ajoutez <code>extension=mongodb.so</code> à votre php.ini dans /opt/lampp/etc/<br>
         Après l'installation, redémarrez XAMPP avec: <code>sudo /opt/lampp/lampp restart</code>");
}

require 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->Other;
    $collection = $db->test_users;
    echo "Connexion réussie à MongoDB ! Base de données 'Other', Collection 'test_users'";
} catch (Exception $e) {
    echo "Erreur de connexion à MongoDB: " . $e->getMessage();
}
?>
