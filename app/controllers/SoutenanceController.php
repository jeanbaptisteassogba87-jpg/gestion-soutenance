<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Jury.php';
require_once __DIR__ . '/../models/MembreJury.php';

session_start();

$action = $_GET['action'] ?? 'list';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ' . BASE_URL . '/app/views/auth/login.php');
    exit();
}

switch ($action) {
    case 'list':
        $role = $_SESSION['role'];
        
        if ($role === 'administrateur') {
            $soutenances = getAllSoutenances($db);
        } elseif ($role === 'membre_jury') {
            $soutenances = getSoutenancesMembreJury($db, $_SESSION['idUtilisateur']);
        } elseif ($role === 'etudiant') {
            $stmt = $db->prepare("
                SELECT s.* 
                FROM Soutenance s
                JOIN Memoire m ON s.idMemoire = m.idMemoire
                WHERE m.idEtudiant = ?
            ");
            $stmt->execute([$_SESSION['idUtilisateur']]);
            $soutenances = $stmt->fetchAll();
        } elseif ($role === 'encadreur') {
            $stmt = $db->prepare("
                SELECT s.*, m.titre, u.nom, u.prenom
                FROM Soutenance s
                JOIN Memoire m ON s.idMemoire = m.idMemoire
                JOIN Etudiant e ON m.idEtudiant = e.idEtudiant
                JOIN Utilisateur u ON e.idEtudiant = u.idUtilisateur
                WHERE m.idEncadreur = ?
            ");
            $stmt->execute([$_SESSION['idUtilisateur']]);
            $soutenances = $stmt->fetchAll();
        } else {
            $soutenances = [];
        }
        
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($soutenances);
            exit();
        }
        
        header('Location: ' . BASE_URL . '/app/views/' . $role . '/dashboard.php');
        break;
        
    case 'get':
        $idSoutenance = $_GET['id'] ?? 0;
        $soutenance = getSoutenanceById($db, $idSoutenance);
        
        if ($soutenance) {
            // Ajouter les infos du jury
            $soutenance['jury'] = getMembresJury($db, $soutenance['idJury']);
            header('Content-Type: application/json');
            echo json_encode($soutenance);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Soutenance non trouvée']);
        }
        break;
        
    case 'calendrier':
        // Pour affichage calendrier (optionnel)
        $soutenances = getAllSoutenances($db);
        $events = [];
        foreach ($soutenances as $s) {
            $events[] = [
                'title' => 'Soutenance #' . $s['idSoutenance'],
                'start' => $s['date'] . 'T' . $s['heure'],
                'extendedProps' => [
                    'salle' => $s['salle'],
                    'note' => $s['note']
                ]
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($events);
        break;
        
    default:
        header('Location: ' . BASE_URL . '/app/views/auth/login.php');
        break;
}
?>