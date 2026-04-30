<?php
    session_start();

    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../config/database.php';

    if(isset($_SESSION['idUtilisateur'])){
        switch($_SESSION['role']){
            case 'administrateur':
                header('Location:' . BASE_URL . '/app/views/admin/dashboard.php');
                break;
            case 'encadreur':
                header('Location:' . BASE_URL . '/app/views/encadreur/dashboard.php');
                break;
            case 'etudiant':
                header('Location:' . BASE_URL . '/app/views/etudiant/dashboard.php');
                break;
            case 'membre_jury':
                header('Location:' . BASE_URL . '/app/views/jury/dashboard.php');
                break;
            default:
                header('Location:' . BASE_URL . '/app/views/auth/login.php');
                break;
        }
        exit();
    } else {
        header('Location:' . BASE_URL . '/app/views/auth/login.php');
        exit();
    }
?>