
<?php

try {
    $db = new PDO(
        'mysql:host=127.0.0.1;dbname=gestion_soutenances;charset=utf8mb4',
        'votre_utilisateur',
        'votre_mot_de_passe',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}
