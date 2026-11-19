
<?php
session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Encadreur.php';
require_once __DIR__ . '/../helpers/security.php';

if (
    !isset($_SESSION['idUtilisateur'])
    || ($_SESSION['role'] ?? '') !== 'encadreur'
) {
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
    case 'rejeter':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        verify_csrf_token();

        $idMemoire = filter_var(
            $_POST['idMemoire'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($idMemoire !== false && $idMemoire > 0) {
            $statut = $action === 'valider' ? 'valide' : 'rejete';

            updateStatutMemoire(
                $db,
                $idMemoire,
                $_SESSION['idUtilisateur'],
                $statut
            );
        }

        header('Location: /app/controllers/EncadreurController.php?action=memoires');
        exit();

    default:
        http_response_code(404);
        exit('Action introuvable.');
}
