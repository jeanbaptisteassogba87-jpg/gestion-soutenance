<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Jury.php';
require_once __DIR__ . '/../models/Utilisateur.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] !== 'administrateur') {
    header('Location: /app/views/auth/login.php');
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    case 'dashboard':
        $soutenances = getAllSoutenances($db);
        $memoires    = getMemoiresEnAttente($db);
        require_once VIEWS_PATH . '/admin/dashboard.php';
        break;

    case 'planifier':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idJury = createJury($db);
            addMembreToJury($db, $idJury, $_POST['president'],   'president');
            addMembreToJury($db, $idJury, $_POST['rapporteur'],  'rapporteur');
            addMembreToJury($db, $idJury, $_POST['examinateur'], 'examinateur');
            createSoutenance($db, [
                'idMemoire'        => $_POST['idMemoire'],
                'idAdministrateur' => $_SESSION['idUtilisateur'],
                'idJury'           => $idJury,
                'date'             => $_POST['date'],
                'heure'            => $_POST['heure'],
                'salle'            => $_POST['salle'],
            ]);
            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }
        $memoires = getMemoiresValides($db);
        $membres  = getAllMembresJury($db);
        require_once VIEWS_PATH . '/admin/planifier_soutenance.php';
        break;

    case 'affecter_jury':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            affecterJury($db, $_POST['idSoutenance'], $_POST['idJury']);
            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }
        $soutenances = getAllSoutenances($db);
        $jurys       = getAllJury($db);
        require_once VIEWS_PATH . '/admin/affecter_jury.php';
        break;

    case 'utilisateurs':
        $type      = $_GET['type'] ?? 'etudiant';
        $etudiants = getAllEtudiants($db);
        $encadreurs= getAllEncadreurs($db);
        $jurys_m   = getAllMembresJury($db);
        require_once VIEWS_PATH . '/admin/utilisateurs.php';
        break;

    case 'creer_utilisateur':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'];

            $idNouvel = createUtilisateur($db, [
                'nom'        => $_POST['nom'],
                'prenom'     => $_POST['prenom'],
                'login'      => $_POST['login'],
                'motDePasse' => $_POST['motDePasse'],
                'role'       => $role,
            ]);

            if ($idNouvel === false) {
                $erreur = 'Ce login est déjà utilisé. Choisissez-en un autre.';
            } else {
                if ($role === 'etudiant') {
                    createEtudiant($db, $idNouvel, $_POST['extra']);
                } elseif ($role === 'encadreur') {
                    createEncadreur($db, $idNouvel, $_POST['extra']);
                } elseif ($role === 'membre_jury') {
                    createMembreJury($db, $idNouvel);
                }
                header('Location: /app/controllers/AdminController.php?action=utilisateurs');
                exit();
            }
        }
        require_once VIEWS_PATH . '/admin/creer_utilisateur.php';
        break;

    case 'modifier_utilisateur':
        $id   = $_GET['id'] ?? null;
        $user = getUserById($db, $id);
        if (!$user) {
            header('Location: /app/controllers/AdminController.php?action=utilisateurs');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            updateUtilisateur($db, $id, [
                'nom'    => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'login'  => $_POST['login'],
            ]);

            if ($user['role'] === 'etudiant') {
                updateEtudiant($db, $id, $_POST['extra']);
            } elseif ($user['role'] === 'encadreur') {
                updateEncadreur($db, $id, $_POST['extra']);
            }

            if (!empty($_POST['motDePasse'])) {
                updateMotDePasse($db, $id, $_POST['motDePasse']);
            }

            header('Location: /app/controllers/AdminController.php?action=utilisateurs');
            exit();
        }
        require_once VIEWS_PATH . '/admin/modifier_utilisateur.php';
        break;

    // ── SUPPRIMER UN UTILISATEUR ─────────────────────────
    case 'supprimer_utilisateur':
        $id   = $_GET['id'] ?? null;
        $role = $_GET['role'] ?? null;
        if ($id && $role) {
            try {
                deleteUtilisateur($db, $id, $role);
            } catch (PDOException $e) {
                $_SESSION['erreur'] = 'Cet utilisateur possède des données liées. Utilisez la réinitialisation complète si vous souhaitez tout effacer.';
            }
        }
        header('Location: /app/controllers/AdminController.php?action=utilisateurs');
        exit();

    // ── REMISE À ZÉRO COMPLÈTE ──────────────────────────
    case 'reinitialiser':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }

        $confirmation = trim($_POST['confirmation'] ?? '');
        $motDePasse   = $_POST['motDePasse'] ?? '';

        if ($confirmation !== 'REINITIALISER' || strlen($motDePasse) < 8) {
            $_SESSION['erreur'] = 'Saisissez « REINITIALISER » et un mot de passe d’au moins 8 caractères.';
            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }

        try {
            $idAdministrateur = reinitialiserApplication($db, $motDePasse);
            $_SESSION['idUtilisateur'] = $idAdministrateur;
            $_SESSION['role'] = 'administrateur';
            $_SESSION['nom'] = 'Administrateur';
            $_SESSION['prenom'] = 'Système';
            $_SESSION['succes'] = 'Réinitialisation terminée. Vous pouvez recréer les comptes.';
        } catch (Throwable $e) {
            $_SESSION['erreur'] = 'La réinitialisation a échoué. Aucune donnée n’a été supprimée.';
        }

        header('Location: /app/controllers/AdminController.php?action=dashboard');
        exit();
}
