<?php
function getEncadreurById($db, $idEncadreur) {
    $stmt = $db->prepare('
        SELECT u.nom, u.prenom, u.login, enc.specialite
        FROM Encadreur enc
        JOIN Utilisateur u ON enc.idEncadreur = u.idUtilisateur
        WHERE enc.idEncadreur = ?
    ');
    $stmt->execute([$idEncadreur]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllEncadreurs($db) {
    $stmt = $db->query('
        SELECT u.nom, u.prenom, u.login, enc.specialite, enc.idEncadreur
        FROM Encadreur enc
        JOIN Utilisateur u ON enc.idEncadreur = u.idUtilisateur
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
