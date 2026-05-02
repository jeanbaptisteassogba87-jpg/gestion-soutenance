<?php
    function getAllMemoires($db) {
        $stmt = $db->query('SELECT * FROM Memoire');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getMemoiresEnAttente($db) {
        $stmt = $db->prepare('SELECT * FROM Memoire WHERE statut = ?');
        $stmt->execute(['en_attente']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getMemoireById($db, $idMemoire) {
        $stmt = $db->prepare('SELECT * FROM Memoire WHERE idMemoire = ?');
        $stmt->execute([$idMemoire]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function createMemoire($db, $data) {
        $stmt = $db->prepare('INSERT INTO Memoire(idEtudiant, idEncadreur, titre, dateDepot, statut) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['idEtudiant'],
            $data['idEncadreur'],
            $data['titre'],
            $data['dateDepot'],
            'en_attente'
        ]);
    }

    function updateStatutMemoire($db, $idMemoire, $statut) {
        $stmt = $db->prepare('UPDATE Memoire SET statut = ? WHERE idMemoire = ?');
        $stmt->execute([$statut, $idMemoire]);
    }
?>