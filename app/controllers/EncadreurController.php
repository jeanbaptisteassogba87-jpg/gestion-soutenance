<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Encadreur.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] !== 'encadreur') {
    header('Location: /app/views/auth/login.php');
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    case 'dashboard':
        $memoires = getMemoiresEncadreur($db, $_SESSION['idUtilisateur']);
        require_once VIEWS_PATH . '/encadreur/dashboard.php';
        break;

    case 'memoires':
        $memoires = getMemoiresEncadreur($db, $_SESSION['idUtilisateur']);
        require_once VIEWS_PATH . '/encadreur/verifier_memoire.php';
        break;

    case 'valider':
        updateStatutMemoire($db, $_GET['id'], 'valide');
        header('Location: /app/controllers/EncadreurController.php?action=memoires');
        exit();

    case 'rejeter':
        updateStatutMemoire($db, $_GET['id'], 'rejete');
        header('Location: /app/controllers/EncadreurController.php?action=memoires');
        exit();
}