<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// LOGOUT
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: /app/views/auth/login.php');
    exit();
}

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $pwd   = $_POST['pwd'];

    $user = getUserByLogin($db, $login);

    if ($user && password_verify($pwd, $user['motDePasse'])) {
        $_SESSION['idUtilisateur'] = $user['idUtilisateur'];
        $_SESSION['role']          = $user['role'];
        $_SESSION['nom']           = $user['nom'];
        $_SESSION['prenom']        = $user['prenom'];

        switch ($user['role']) {
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
        }
        exit();
    } else {
        header('Location: /app/views/auth/login.php?error=1');
        exit();
    }
}

// Accès direct sans POST
header('Location: /app/views/auth/login.php');
exit();