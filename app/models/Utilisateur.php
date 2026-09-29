<?php

function getUserByLogin($db, $login) {
    $stmt = $db->prepare('SELECT * FROM Utilisateur WHERE login = ?');
    $stmt->execute([$login]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserById($db, $id) {
    $stmt = $db->prepare('SELECT * FROM Utilisateur WHERE idUtilisateur = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllEtudiants($db) {
    $stmt = $db->query('
        SELECT u.*, e.filiere
        FROM Utilisateur u
        JOIN Etudiant e ON u.idUtilisateur = e.idEtudiant
        ORDER BY u.nom, u.prenom
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllEncadreurs($db) {
    $stmt = $db->query('
        SELECT u.*, enc.specialite, enc.idEncadreur
        FROM Utilisateur u
        JOIN Encadreur enc ON u.idUtilisateur = enc.idEncadreur
        ORDER BY u.nom, u.prenom
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createUtilisateur($db, $data) {
    $check = $db->prepare('SELECT idUtilisateur FROM Utilisateur WHERE login = ?');
    $check->execute([$data['login']]);
    if ($check->fetch()) return false; // 

    $hash = password_hash($data['motDePasse'], PASSWORD_DEFAULT);
    $stmt = $db->prepare('
        INSERT INTO Utilisateur (nom, prenom, login, motDePasse, role)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([$data['nom'], $data['prenom'], $data['login'], $hash, $data['role']]);
    return $db->lastInsertId();
}

function createEtudiant($db, $idUtilisateur, $filiere) {
    $stmt = $db->prepare('INSERT INTO Etudiant (idEtudiant, filiere) VALUES (?, ?)');
    $stmt->execute([$idUtilisateur, $filiere]);
}

function createEncadreur($db, $idUtilisateur, $specialite) {
    $stmt = $db->prepare('INSERT INTO Encadreur (idEncadreur, specialite) VALUES (?, ?)');
    $stmt->execute([$idUtilisateur, $specialite]);
}

function createMembreJury($db, $idUtilisateur) {
    $stmt = $db->prepare('INSERT INTO MembreJury (idMembreJury) VALUES (?)');
    $stmt->execute([$idUtilisateur]);
}

// ── Modifier un utilisateur ──────────────────────────────

function updateUtilisateur($db, $id, $data) {
    $stmt = $db->prepare('
        UPDATE Utilisateur SET nom = ?, prenom = ?, login = ?
        WHERE idUtilisateur = ?
    ');
    $stmt->execute([$data['nom'], $data['prenom'], $data['login'], $id]);
}

function updateMotDePasse($db, $id, $nouveauMdp) {
    $hash = password_hash($nouveauMdp, PASSWORD_DEFAULT);
    $stmt = $db->prepare('UPDATE Utilisateur SET motDePasse = ? WHERE idUtilisateur = ?');
    $stmt->execute([$hash, $id]);
}

function updateEtudiant($db, $id, $filiere) {
    $stmt = $db->prepare('UPDATE Etudiant SET filiere = ? WHERE idEtudiant = ?');
    $stmt->execute([$filiere, $id]);
}

function updateEncadreur($db, $id, $specialite) {
    $stmt = $db->prepare('UPDATE Encadreur SET specialite = ? WHERE idEncadreur = ?');
    $stmt->execute([$specialite, $id]);
}

// ── Supprimer un utilisateur ─────────────────────────────

function deleteUtilisateur($db, $id, $role) {
    // Supprimer d'abord la table fille (contrainte FK)
    switch ($role) {
        case 'etudiant':
            $db->prepare('DELETE FROM Etudiant WHERE idEtudiant = ?')->execute([$id]);
            break;
        case 'encadreur':
            $db->prepare('DELETE FROM Encadreur WHERE idEncadreur = ?')->execute([$id]);
            break;
        case 'membre_jury':
            $db->prepare('DELETE FROM MembreJury WHERE idMembreJury = ?')->execute([$id]);
            break;
    }
    // Puis supprimer l'utilisateur
    $db->prepare('DELETE FROM Utilisateur WHERE idUtilisateur = ?')->execute([$id]);
}

/**
 * Supprime toutes les données fonctionnelles et recrée le seul compte nécessaire
 * pour repartir sur une base propre. Les suppressions sont ordonnées afin de
 * respecter les clés étrangères.
 */
function reinitialiserApplication($db, $motDePasseAdmin) {
    $db->beginTransaction();

    try {
        foreach ([
            'Soutenance',
            'Memoire',
            'RoleJury',
            'Jury',
            'MembreJury',
            'Etudiant',
            'Encadreur',
            'Administrateur',
            'Utilisateur',
        ] as $table) {
            $db->exec("DELETE FROM $table");
        }

        $stmt = $db->prepare(
            'INSERT INTO Utilisateur (nom, prenom, login, motDePasse, role)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            'Administrateur',
            'Système',
            'admin',
            password_hash($motDePasseAdmin, PASSWORD_DEFAULT),
            'administrateur',
        ]);

        $idAdministrateur = (int) $db->lastInsertId();
        $db->prepare('INSERT INTO Administrateur (idAdministrateur) VALUES (?)')
           ->execute([$idAdministrateur]);

        $db->commit();
        return $idAdministrateur;
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        throw $e;
    }
}
