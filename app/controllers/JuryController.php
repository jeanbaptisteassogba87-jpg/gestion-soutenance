<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/MembreJury.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] !== 'membre_jury') {
    header('Location: /app/views/auth/login.php');
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    case 'dashboard':
        $soutenances = getSoutenancesMembreJury($db, $_SESSION['idUtilisateur']);
        require_once VIEWS_PATH . '/jury/dashboard.php';
        break;

    case 'planning':
        $soutenances = getAllSoutenances($db);
        require_once VIEWS_PATH . '/jury/consulter_planning.php';
        break;

    case 'evaluer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $db->prepare('UPDATE Soutenance SET note = ?, mention = ? WHERE idSoutenance = ?');
            $stmt->execute([$_POST['note'], $_POST['mention'], $_POST['idSoutenance']]);
            header('Location: /app/controllers/JuryController.php?action=dashboard');
            exit();
        }
        $soutenances = getSoutenancesMembreJury($db, $_SESSION['idUtilisateur']);
        require_once VIEWS_PATH . '/jury/evaluer_etudiant.php';
        break;  

    case 'deliberer':
        $soutenances = getSoutenancesMembreJury($db, $_SESSION['idUtilisateur']);
        require_once VIEWS_PATH . '/jury/deliberer.php';
        break;
}