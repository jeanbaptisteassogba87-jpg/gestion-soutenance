
<?php

session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

if (
    !isset($_SESSION['idUtilisateur'], $_SESSION['role'])
    || !is_numeric($_SESSION['idUtilisateur'])
) {
    http_response_code(401);
    exit('Connexion requise.');
}

$idUtilisateur = (int) $_SESSION['idUtilisateur'];
$role = $_SESSION['role'];

$idMemoire = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idMemoire || $idMemoire <= 0) {
    http_response_code(400);
    exit('Identifiant invalide.');
}

// Récupérer le mémoire et vérifier les autorisations.
$sql = "
    SELECT m.fichier
    FROM Memoire m
    WHERE m.idMemoire = ?
";

$params = [$idMemoire];

switch ($role) {
    case 'administrateur':
        break;

    case 'etudiant':
        $sql .= " AND (m.idEtudiant = ? OR m.idEtudiant2 = ?)";
        $params[] = $idUtilisateur;
        $params[] = $idUtilisateur;
        break;

    case 'encadreur':
        $sql .= " AND m.idEncadreur = ?";
        $params[] = $idUtilisateur;
        break;

    case 'membre_jury':
        $sql .= "
            AND EXISTS (
                SELECT 1
                FROM Soutenance s
                JOIN RoleJury rj ON rj.idJury = s.idJury
                WHERE s.idMemoire = m.idMemoire
                AND rj.idMembreJury = ?
            )
        ";
        $params[] = $idUtilisateur;
        break;

    default:
        http_response_code(403);
        exit('Accès interdit.');
}

$stmt = $db->prepare($sql);
$stmt->execute($params);

$memoire = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$memoire) {
    http_response_code(404);
    exit('Mémoire introuvable ou accès interdit.');
}

// Le nom du fichier provient exclusivement de la base de données.
$nomFichier = $memoire['fichier'];

if (
    !is_string($nomFichier)
    || !preg_match('/\A[a-f0-9]{32}\.pdf\z/', $nomFichier)
) {
    http_response_code(404);
    exit('Fichier invalide.');
}

$chemin = __DIR__ . '/../../storage/memoires/' . $nomFichier;

if (!is_file($chemin) || !is_readable($chemin)) {
    http_response_code(404);
    exit('Fichier introuvable.');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="memoire.pdf"');
header('Content-Length: ' . filesize($chemin));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, no-cache, must-revalidate');

readfile($chemin);
exit();
