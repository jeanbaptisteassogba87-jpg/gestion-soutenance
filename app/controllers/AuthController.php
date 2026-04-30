<?php
    session_start();
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../models/Utilisateur.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $pwd   = $_POST['pwd'];

        $user = getUserByLogin($db, $login);

        if($user && password_verify($pwd, $user['motDePasse'])) {
            $_SESSION['idUtilisateur'] = $user['idUtilisateur'];
            $_SESSION['role']          = $user['role'];
            $_SESSION['nom']           = $user['nom'];

                header('Location: http://localhost:8000/public/index.php');
            exit();
        } else {
            header('Location: ' . BASE_URL . '/app/views/auth/login.php?error=1');
            exit();
        }
    }

     if(isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_start();
        session_destroy();
        header('Location: ../views/auth/login.php');
        exit();
    }
?>