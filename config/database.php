<?php
    try {
        $db = new PDO('mysql:host=127.0.0.1;dbname=gestion_soutenances', 'root', '#ASSOGBAjb94#');
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données.");
    }
?>