
<?php
session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/MembreJury.php';
require_once __DIR__ . '/../helpers/security.php';

if (
    !isset($_SESSION['idUtilisateur'])
    || ($_SESSION['role'] ?? '') !== 'membre_jury'
) {
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
            verify_csrf_token();

            $idSoutenance = filter_var(
                $_POST['idSoutenance'] ?? null,
                FILTER_VALIDATE_INT
            );

            $note = filter_var(
                $_POST['note'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (
                $idSoutenance === false || $idSoutenance <= 0
                || $note === false || $note < 0 || $note > 20
            ) {
                $_SESSION['erreur'] = 'La note doit être un nombre entier entre 0 et 20.';
            } else {
                if ($note >= 18) {
                    $mention = 'Excellent';
                } elseif ($note >= 16) {
                    $mention = 'Tres Bien';
                } elseif ($note >= 14) {
                    $mention = 'Bien';
                } elseif ($note >= 12) {
                    $mention = 'Assez Bien';
                } elseif ($note >= 10) {
                    $mention = 'Passable';
                } else {
                    $mention = null;
                }

                $ok = enregistrerNoteMembreJury(
                    $db,
                    $idSoutenance,
                    $_SESSION['idUtilisateur'],
                    $note,
                    $mention
                );

                if (!$ok) {
                    $_SESSION['erreur'] = 'Cette soutenance ne vous est pas affectée.';
                } else {
                    $_SESSION['succes'] = 'Note enregistrée.';
                }
            }

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

    default:
        http_response_code(404);
        exit('Action introuvable.');
}
