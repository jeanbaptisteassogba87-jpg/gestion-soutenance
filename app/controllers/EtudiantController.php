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

            $erreur = null;
            $fichier = null;

            if (
                !isset($_FILES['fichier']) ||
                $_FILES['fichier']['error'] !== UPLOAD_ERR_OK
            ) {
                $erreur = "Veuillez sélectionner un fichier PDF valide.";
            } else {
                $upload = $_FILES['fichier'];

                if ($upload['size'] > 10 * 1024 * 1024) {
                    $erreur = "Le fichier ne doit pas dépasser 10 Mo.";
                } else {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $typeMime = $finfo->file($upload['tmp_name']);

                    if ($typeMime !== 'application/pdf') {
                        $erreur = "Le fichier doit être un véritable PDF.";
                    } else {
                        $nomFichier = bin2hex(random_bytes(16)) . '.pdf';
                        $destination = __DIR__ . '/../../public/uploads/memoires/' . $nomFichier;

                        if (move_uploaded_file($upload['tmp_name'], $destination)) {
                            $fichier = $nomFichier;
                        } else {
                            $erreur = "Impossible d'enregistrer le fichier.";
                        }
                    }
                }
            }

            if ($erreur !== null) {
                $encadreurs = getAllEncadreurs($db);
                $etudiants = array_filter(getAllEtudiants($db), function ($e) {
                    return $e['idEtudiant'] != $_SESSION['idUtilisateur'];
                });
                require_once VIEWS_PATH . '/etudiant/deposer_memoire.php';
                break;
            }

            createMemoire($db, [
                'idEtudiant'  => $_SESSION['idUtilisateur'],
                'idEtudiant2' => !empty($_POST['idEtudiant2'])
                ? (int) $_POST['idEtudiant2']
                : null,
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