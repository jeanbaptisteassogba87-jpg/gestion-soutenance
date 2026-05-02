<?php
function getEtudiantById($db, $idEtudiant) {
    $stmt = $db->prepare('
        SELECT u.nom, u.prenom, u.login, e.filiere
        FROM Etudiant e
        JOIN Utilisateur u ON e.idEtudiant = u.idUtilisateur
        WHERE e.idEtudiant = ?
    ');
    $stmt->execute([$idEtudiant]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllEtudiants($db) {
    $stmt = $db->query('
        SELECT u.nom, u.prenom, u.login, e.filiere, e.idEtudiant
        FROM Etudiant e
        JOIN Utilisateur u ON e.idEtudiant = u.idUtilisateur
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}