<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['idUtilisateur'])) {
    switch ($_SESSION['role']) {
        case 'administrateur':
            header('Location: /app/controllers/AdminController.php');
            break;
        case 'etudiant':
            header('Location: /app/controllers/EtudiantController.php');
            break;
        case 'encadreur':
            header('Location: /app/controllers/EncadreurController.php');
            break;
        case 'membre_jury':
            header('Location: /app/controllers/JuryController.php');
            break;
        default:
            header('Location: /app/views/accueil.php');
            break;
    }
    exit();
}

// Pas connecté → page d'accueil publique
header('Location: /app/views/accueil.php');
exit();