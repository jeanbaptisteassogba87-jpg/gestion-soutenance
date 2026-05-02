<?php
    function getAllSoutenances($db) {
        $stmt = $db->query('SELECT * FROM Soutenance');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function createSoutenance($db, $data) {
        $stmt = $db->prepare('INSERT INTO Soutenance(idMemoire, idAdministrateur, idJury, date, heure, salle) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['idMemoire'],
            $data['idAdministrateur'],
            $data['idJury'],
            $data['date'],
            $data['heure'],
            $data['salle']
        ]);
    }

    function affecterJury($db, $idSoutenance, $idJury) {
        $stmt = $db->prepare('UPDATE Soutenance SET idJury = ? WHERE idSoutenance = ?');
        $stmt->execute([$idJury, $idSoutenance]);
    }

    function getSoutenanceById($db, $idSoutenance) {
        $stmt = $db->prepare('SELECT * FROM Soutenance WHERE idSoutenance = ?');
        $stmt->execute([$idSoutenance]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>