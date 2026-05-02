<?php
    function getAllJury($db) {
        $stmt = $db->query('SELECT * FROM Jury');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function createJury($db) {
        $stmt = $db->prepare('INSERT INTO Jury() VALUES ()');
        $stmt->execute();
        return $db->lastInsertId();
    }

    function addMembreToJury($db, $idJury, $idMembreJury, $role) {
        $stmt = $db->prepare('INSERT INTO RoleJury(idJury, idMembreJury, role) VALUES (?, ?, ?)');
        $stmt->execute([$idJury, $idMembreJury, $role]);
    }

    function getMembresJury($db, $idJury) {
        $stmt = $db->prepare('
            SELECT mj.*, rj.role, u.nom, u.prenom 
            FROM RoleJury rj
            JOIN MembreJury mj ON rj.idMembreJury = mj.idMembreJury
            JOIN Utilisateur u ON mj.idMembreJury = u.idUtilisateur
            WHERE rj.idJury = ?
        ');
        $stmt->execute([$idJury]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllMembresJury($db) {
    $stmt = $db->query('
        SELECT mj.idMembreJury, u.nom, u.prenom 
        FROM MembreJury mj
        JOIN Utilisateur u ON mj.idMembreJury = u.idUtilisateur
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>