<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Encadreur.php';
require_once __DIR__ . '/../models/Etudiant.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] !== 'etudiant') {
    header('Location: /app/views/auth/login.php');
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    case 'dashboard':
        $mesMemoires = getMemoireByEtudiant($db, $_SESSION['idUtilisateur']);
        $soutenances = getAllSoutenances($db);
        require_once VIEWS_PATH . '/etudiant/dashboard.php';
        break;

    case 'deposer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $fichier = null;
            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
                $ext = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION);
                if (strtolower($ext) !== 'pdf') {
                    $erreur = "Seuls les fichiers PDF sont acceptés.";
                    $encadreurs = getAllEncadreurs($db);
                    $etudiants  = getAllEtudiants($db);
                    require_once VIEWS_PATH . '/etudiant/deposer_memoire.php';
                    break;
                }
                $nomFichier = uniqid('memoire_') . '.pdf';
                $destination = __DIR__ . '/../../public/uploads/memoires/' . $nomFichier;
                move_uploaded_file($_FILES['fichier']['tmp_name'], $destination);
                $fichier = $nomFichier;
            }

            createMemoire($db, [
                'idEtudiant'  => $_SESSION['idUtilisateur'],
                'idEtudiant2' => $_POST['idEtudiant2'],
                'idEncadreur' => $_POST['idEncadreur'],
                'titre'       => $_POST['titre'],
                'dateDepot'   => date('Y-m-d'),
                'fichier'     => $fichier,
            ]);

            header('Location: /app/controllers/EtudiantController.php?action=dashboard');
            exit();
        }
        $encadreurs = getAllEncadreurs($db);
        
        $etudiants = array_filter(getAllEtudiants($db), function($e) {
            return $e['idEtudiant'] != $_SESSION['idUtilisateur'];
        });
        require_once VIEWS_PATH . '/etudiant/deposer_memoire.php';
        break;

    case 'planning':
        $soutenances = getAllSoutenances($db);
        require_once VIEWS_PATH . '/etudiant/consulter_planning.php';
        break;
}