<?php
function getRolesByJury($db, $idJury) {
    $stmt = $db->prepare('
        SELECT rj.role, u.nom, u.prenom, mj.idMembreJury
        FROM RoleJury rj
        JOIN MembreJury mj ON rj.idMembreJury = mj.idMembreJury
        JOIN Utilisateur u ON mj.idMembreJury = u.idUtilisateur
        WHERE rj.idJury = ?
    ');
    $stmt->execute([$idJury]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}