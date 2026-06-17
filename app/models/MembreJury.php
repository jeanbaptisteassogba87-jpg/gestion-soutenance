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