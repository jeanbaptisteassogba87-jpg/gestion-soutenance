<?php

function getAllSoutenances($db) {
    $stmt = $db->query('
        SELECT s.*, m.titre, m.fichier,
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Soutenance s
        JOIN Memoire m ON s.idMemoire = m.idMemoire
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSoutenancesMembreJury($db, $idMembreJury) {
    $stmt = $db->prepare('
        SELECT s.*, m.titre, m.fichier,
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2,
               rj.role
        FROM Soutenance s
        JOIN Memoire m ON s.idMemoire = m.idMemoire
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        JOIN RoleJury rj ON s.idJury = rj.idJury
        WHERE rj.idMembreJury = ?
    ');
    $stmt->execute([$idMembreJury]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createSoutenance($db, $data) {
    $stmt = $db->prepare('
        INSERT INTO Soutenance(idMemoire, idAdministrateur, idJury, date, heure, salle)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $data['idMemoire'],
        $data['idAdministrateur'],
        $data['idJury'],
        $data['date'],
        $data['heure'],
        $data['salle'],
    ]);
}

function affecterJury($db, $idSoutenance, $idJury) {
    $stmt = $db->prepare('UPDATE Soutenance SET idJury = ? WHERE idSoutenance = ?');
    $stmt->execute([$idJury, $idSoutenance]);
}

function getSoutenanceById($db, $idSoutenance) {
    $stmt = $db->prepare('
        SELECT s.*, m.titre, m.fichier,
               u1.nom AS nom_etudiant1, u1.prenom AS prenom_etudiant1,
               u2.nom AS nom_etudiant2, u2.prenom AS prenom_etudiant2
        FROM Soutenance s
        JOIN Memoire m ON s.idMemoire = m.idMemoire
        JOIN Utilisateur u1 ON m.idEtudiant = u1.idUtilisateur
        LEFT JOIN Utilisateur u2 ON m.idEtudiant2 = u2.idUtilisateur
        WHERE s.idSoutenance = ?
    ');
    $stmt->execute([$idSoutenance]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function enregistrerNoteMembreJury($db, $idSoutenance, $idMembreJury, $note, $mention) {
    $check = $db->prepare('
        SELECT s.idSoutenance
        FROM Soutenance s
        JOIN RoleJury rj ON rj.idJury = s.idJury
        WHERE s.idSoutenance = ?
          AND rj.idMembreJury = ?
        LIMIT 1
    ');

    $check->execute([$idSoutenance, $idMembreJury]);

    if (!$check->fetch()) {
        return false;
    }

    $stmt = $db->prepare('
        UPDATE Soutenance
        SET note = ?, mention = ?
        WHERE idSoutenance = ?
    ');

    $stmt->execute([$note, $mention, $idSoutenance]);

    return true;
}
