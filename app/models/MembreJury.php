<?php
function getMembreJuryById($db, $idMembreJury) {
    $stmt = $db->prepare('
        SELECT u.nom, u.prenom, u.login
        FROM MembreJury mj
        JOIN Utilisateur u ON mj.idMembreJury = u.idUtilisateur
        WHERE mj.idMembreJury = ?
    ');
    $stmt->execute([$idMembreJury]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSoutenancesMembreJury($db, $idMembreJury) {
    $stmt = $db->prepare('
        SELECT s.*, rj.role
        FROM Soutenance s
        JOIN RoleJury rj ON s.idJury = rj.idJury
        WHERE rj.idMembreJury = ?
    ');
    $stmt->execute([$idMembreJury]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}