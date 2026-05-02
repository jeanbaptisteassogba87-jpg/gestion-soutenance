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

function getMemoiresEncadreur($db, $idEncadreur) {
    $stmt = $db->prepare('
        SELECT m.*, u.nom, u.prenom
        FROM Memoire m
        JOIN Etudiant e ON m.idEtudiant = e.idEtudiant
        JOIN Utilisateur u ON e.idEtudiant = u.idUtilisateur
        WHERE m.idEncadreur = ?
    ');
    $stmt->execute([$idEncadreur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}