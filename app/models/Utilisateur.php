<?php
    function getUserByLogin($db , $login){

        $stmt = $db->prepare('SELECT * From Utilisateur where login = ?');
        $stmt->execute([$login]);

        return  $stmt->fetch();
    }
        
?>  