<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../models/Encadreur.php';

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
        // Mettre à jour le statut (pour encadreur)
        if ($_SESSION['role'] === 'encadreur' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $idMemoire = $_POST['idMemoire'] ?? 0;
            $statut = $_POST['statut'] ?? null;
            
            if ($statut && ($statut === 'valide' || $statut === 'rejete')) {
                updateStatutMemoire($db, $idMemoire, $statut);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Statut invalide']);
            }
        }
        break;
        
    default:
        header('Location: ' . BASE_URL . '/app/views/auth/login.php');
        break;
}
?>