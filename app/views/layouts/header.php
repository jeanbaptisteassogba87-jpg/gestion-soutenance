
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('BASE_URL')) require_once __DIR__ . '/../../../config/config.php';

if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: /app/views/auth/login.php');
    exit();
}

require_once __DIR__ . '/../../helpers/security.php';
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UATM GASA — Gestion des soutenances</title>

    <link rel="stylesheet" href="/public/css/style.css">

    <!-- Icônes Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<button class="hamburger" id="hamburger"
        onclick="toggleSidebar()" aria-label="Ouvrir le menu">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="wrapper">

<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <h2><i class="fa-solid fa-graduation-cap"></i> UATM GASA</h2>
        <p>Gestion des soutenances</p>
    </div>

    <nav>
        <div class="sidebar-section">Menu</div>

        <?php if ($role === 'administrateur'): ?>

            <a href="/app/controllers/AdminController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a href="/app/controllers/AdminController.php?action=planifier">
                <i class="fa-solid fa-calendar-plus"></i> Planifier une soutenance
            </a>
            <a href="/app/controllers/AdminController.php?action=affecter_jury">
                <i class="fa-solid fa-users"></i> Changer un jury
            </a>

            <div class="sidebar-section">Gestion</div>

            <a href="/app/controllers/AdminController.php?action=utilisateurs&type=etudiant">
                <i class="fa-solid fa-user-graduate"></i> Etudiants
            </a>
            <a href="/app/controllers/AdminController.php?action=utilisateurs&type=encadreur">
                <i class="fa-solid fa-chalkboard-user"></i> Encadreurs
            </a>
            <a href="/app/controllers/AdminController.php?action=utilisateurs&type=membre_jury">
                <i class="fa-solid fa-scale-balanced"></i> Membres jury
            </a>

        <?php elseif ($role === 'etudiant'): ?>

            <a href="/app/controllers/EtudiantController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a href="/app/controllers/EtudiantController.php?action=deposer">
                <i class="fa-solid fa-file-pdf"></i> Déposer mon mémoire
            </a>
            <a href="/app/controllers/EtudiantController.php?action=planning">
                <i class="fa-solid fa-calendar-days"></i> Planning des soutenances
            </a>

        <?php elseif ($role === 'encadreur'): ?>

            <a href="/app/controllers/EncadreurController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a href="/app/controllers/EncadreurController.php?action=memoires">
                <i class="fa-solid fa-file-circle-check"></i> Mémoires à vérifier
            </a>

        <?php elseif ($role === 'membre_jury'): ?>

            <a href="/app/controllers/JuryController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a href="/app/controllers/JuryController.php?action=planning">
                <i class="fa-solid fa-calendar-days"></i> Planning des soutenances
            </a>
            <a href="/app/controllers/JuryController.php?action=evaluer">
                <i class="fa-solid fa-pen-to-square"></i> Evaluer un étudiant
            </a>
            <a href="/app/controllers/JuryController.php?action=deliberer">
                <i class="fa-solid fa-scale-balanced"></i> Délibération
            </a>

        <?php endif; ?>
    </nav>

    <div class="sidebar-bottom">

        <div class="sidebar-section"
             style="color:#4a5f75;padding:14px 12px 6px;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;">
            Compte
        </div>

        <form action="/app/controllers/AuthController.php?action=logout" method="POST">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

            <button type="submit" class="btn btn-outline">
                <i class="fa-solid fa-right-from-bracket"></i> Se déconnecter
            </button>
        </form>

    </div>
</aside>

<main class="main">

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
}
</script>
