<?php

function getAllMemoires($db) {
    $stmt = $db->query('
        SELECT m.*, 
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Memoire m
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMemoiresValides($db) {
    $stmt = $db->query('
        SELECT m.*, 
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Memoire m
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        WHERE m.statut = "valide"
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMemoiresEnAttente($db) {
    $stmt = $db->query('
        SELECT m.*, 
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Memoire m
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        WHERE m.statut = "en_attente"
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMemoireById($db, $idMemoire) {
    $stmt = $db->prepare('
        SELECT m.*, 
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Memoire m
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        WHERE m.idMemoire = ?
    ');
    $stmt->execute([$idMemoire]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMemoireByEtudiant($db, $idEtudiant) {
    $stmt = $db->prepare('
        SELECT m.*, 
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Memoire m
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        WHERE m.idEtudiant = ? OR m.idEtudiant2 = ?
    ');
    $stmt->execute([$idEtudiant, $idEtudiant]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMemoire($db, $data) {
    $stmt = $db->prepare('
        INSERT INTO Memoire(idEtudiant, idEtudiant2, idEncadreur, titre, dateDepot, statut, fichier)
        VALUES (?, ?, ?, ?, ?, "en_attente", ?)
    ');
    $stmt->execute([
        $data['idEtudiant'],
        $data['idEtudiant2'],
        $data['idEncadreur'],
        $data['titre'],
        $data['dateDepot'],
        $data['fichier'],
    ]);
}

function updateStatutMemoire($db, $idMemoire, $statut) {
    $stmt = $db->prepare('UPDATE Memoire SET statut = ? WHERE idMemoire = ?');
    $stmt->execute([$statut, $idMemoire]);
}

function getMemoiresEncadreur($db, $idEncadreur) {
    $stmt = $db->prepare('
        SELECT m.*, 
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Memoire m
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        WHERE m.idEncadreur = ?
    ');
    $stmt->execute([$idEncadreur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}