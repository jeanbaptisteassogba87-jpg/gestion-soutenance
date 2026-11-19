<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../models/Encadreur.php';
require_once __DIR__ . '/../helpers/security.php';
session_start();

$action = $_GET['action'] ?? 'list';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ' . BASE_URL . '/app/views/auth/login.php');
    exit();
}

switch ($action) {
    case 'list':
        // Récupérer les mémoires selon le rôle
        $role = $_SESSION['role'];
        
        if ($role === 'administrateur') {
            $memoires = getAllMemoires($db);
        } elseif ($role === 'encadreur') {
            $memoires = getMemoiresEncadreur($db, $_SESSION['idUtilisateur']);
        } elseif ($role === 'etudiant') {
            $stmt = $db->prepare("SELECT * FROM Memoire WHERE idEtudiant = ?");
            $stmt->execute([$_SESSION['idUtilisateur']]);
            $memoires = $stmt->fetchAll();
        } else {
            $memoires = [];
        }
        
        // Retourner en JSON pour les appels AJAX
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($memoires);
            exit();
        }
        
        header('Location: ' . BASE_URL . '/app/views/' . $role . '/dashboard.php');
        break;
        
    case 'get':
        $idMemoire = $_GET['id'] ?? 0;
        $memoire = getMemoireById($db, $idMemoire);
        
        if ($memoire) {
            header('Content-Type: application/json');
            echo json_encode($memoire);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Mémoire non trouvé']);
        }
        break;
        
        case 'statut':

        header('Content-Type: application/json');

        // Seul un encadreur peut modifier le statut
        if ($_SESSION['role'] !== 'encadreur') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Accès interdit.'
            ]);
            exit();
        }

        // Vérifier la méthode HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Méthode non autorisée.'
            ]);
            exit();
        }

        // Vérifier le jeton CSRF
        $token = $_POST['csrf_token'] ?? '';

        if (
            !is_string($token)
            || empty($_SESSION['csrf_token'])
            || !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Requête invalide ou expirée.'
            ]);
            exit();
        }

        // Valider l'identifiant du mémoire
        $idMemoire = filter_var(
            $_POST['idMemoire'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$idMemoire || $idMemoire < 1) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Identifiant du mémoire invalide.'
            ]);
            exit();
        }

        // Vérifier le statut demandé
        $statut = $_POST['statut'] ?? null;

        if (!is_string($statut) || !in_array($statut, ['valide', 'rejete'], true)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Statut invalide.'
            ]);
            exit();
        }

        // Modifier uniquement un mémoire appartenant à cet encadreur
        $success = updateStatutMemoire(
            $db,
            $idMemoire,
            $_SESSION['idUtilisateur'],
            $statut
        );

        if (!$success) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Mémoire introuvable, non autorisé ou déjà traité.'
            ]);
            exit();
        }

        echo json_encode([
            'success' => true,
            'message' => 'Statut du mémoire mis à jour.'
        ]);

        exit();
        
    default:
        header('Location: ' . BASE_URL . '/app/views/auth/login.php');
        break;
}
?>