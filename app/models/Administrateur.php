<?php
function getAdministrateurById($db, $idAdmin) {
    $stmt = $db->prepare('
        SELECT u.nom, u.prenom, u.login
        FROM Administrateur a
        JOIN Utilisateur u ON a.idAdministrateur = u.idUtilisateur
        WHERE a.idAdministrateur = ?
    ');
    $stmt->execute([$idAdmin]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}