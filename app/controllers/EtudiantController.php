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

        $erreur = null;
        $encadreurs = getAllEncadreurs($db);

        $etudiants = array_values(array_filter(
            getAllEtudiants($db),
            function ($e) {
                return (int) $e['idEtudiant'] !== (int) $_SESSION['idUtilisateur'];
            }
        ));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1. Validation du titre
            $titre = trim($_POST['titre'] ?? '');

            if ($titre === '' || mb_strlen($titre) > 255) {
                $erreur = "Le titre est obligatoire et ne doit pas dépasser 255 caractères.";
            }

            // 2. Validation de l'encadreur
            $encadreurBrut = $_POST['idEncadreur'] ?? '';

            $idEncadreur = is_string($encadreurBrut) && ctype_digit($encadreurBrut)
                ? (int) $encadreurBrut
                : 0;

            $idsEncadreurs = array_map('intval', array_column($encadreurs, 'idEncadreur'));

            if ($erreur === null && !in_array($idEncadreur, $idsEncadreurs, true)) {
                $erreur = "Veuillez sélectionner un encadreur valide.";
            }

            // 3. Validation du binôme (facultatif)
            $binomeBrut = $_POST['idEtudiant2'] ?? '';

            if ($binomeBrut === '') {
                $idEtudiant2 = null;
            } else {
                $idEtudiant2 = is_string($binomeBrut) && ctype_digit($binomeBrut)
                    ? (int) $binomeBrut
                    : 0;

                $idsEtudiants = array_map('intval', array_column($etudiants, 'idEtudiant'));

                if ($erreur === null && !in_array($idEtudiant2, $idsEtudiants, true)) {
                    $erreur = "Le binôme sélectionné n'est pas valide.";
                }
            }

            // 4. Validation du fichier PDF
            $fichier = null;
            $upload = $_FILES['fichier'] ?? null;

            if ($erreur === null) {

                if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
                    $erreur = "Veuillez sélectionner un fichier PDF valide.";

                } elseif ($upload['size'] > 10 * 1024 * 1024) {
                    $erreur = "Le fichier ne doit pas dépasser 10 Mo.";

                } else {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $typeMime = $finfo->file($upload['tmp_name']);

                    $signature = file_get_contents($upload['tmp_name'], false, null, 0, 5);

                    if ($typeMime !== 'application/pdf' || $signature !== '%PDF-') {
                        $erreur = "Le fichier doit être un véritable PDF.";
                    }
                }
            }

            // 5. Enregistrement du fichier et du mémoire
            if ($erreur === null) {

                $nomFichier = bin2hex(random_bytes(16)) . '.pdf';
                $destination = __DIR__ . '/../../storage/memoires/' . $nomFichier;

                if (!move_uploaded_file($upload['tmp_name'], $destination)) {
                    $erreur = "Impossible d'enregistrer le fichier.";

                } else {
                    try {
                        createMemoire($db, [
                            'idEtudiant'  => (int) $_SESSION['idUtilisateur'],
                            'idEtudiant2' => $idEtudiant2,
                            'idEncadreur' => $idEncadreur,
                            'titre'       => $titre,
                            'dateDepot'   => date('Y-m-d'),
                            'fichier'     => $nomFichier,
                        ]);

                        header('Location: /app/controllers/EtudiantController.php?action=dashboard');
                        exit();

                    } catch (Throwable $e) {
                        // Supprimer le PDF si l'insertion en base échoue
                        if (is_file($destination)) {
                            unlink($destination);
                        }

                        error_log($e->getMessage());
                        $erreur = "Une erreur est survenue lors de l'enregistrement du mémoire.";
                    }
                }
            }
        }

        require_once VIEWS_PATH . '/etudiant/deposer_memoire.php';
        break;

    case 'planning':
        $soutenances = getAllSoutenances($db);
        require_once VIEWS_PATH . '/etudiant/consulter_planning.php';
        break;
}