<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('BASE_URL')) require_once __DIR__ . '/../../../config/config.php';
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: /app/views/auth/login.php');
    exit();
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UATM GASA — Gestion des soutenances</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<button class="hamburger" id="hamburger" onclick="toggleSidebar()">☰</button>
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="wrapper">

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h2>🎓 UATM GASA</h2>
        <p>Gestion des soutenances</p>
    </div>

    <nav>
        <div class="sidebar-section">Menu</div>

        <?php if ($role === 'administrateur'): ?>
            <a href="/app/controllers/AdminController.php?action=dashboard">📊 Tableau de bord</a>
            <a href="/app/controllers/AdminController.php?action=planifier">📅 Planifier une soutenance</a>
            <a href="/app/controllers/AdminController.php?action=affecter_jury">👥 Changer un jury</a>
            <div class="sidebar-section">Gestion</div>
            <a href="/app/controllers/AdminController.php?action=utilisateurs&type=etudiant">🎓 Etudiants</a>
            <a href="/app/controllers/AdminController.php?action=utilisateurs&type=encadreur">👨‍🏫 Encadreurs</a>
            <a href="/app/controllers/AdminController.php?action=utilisateurs&type=membre_jury">⚖️ Membres jury</a>

        <?php elseif ($role === 'etudiant'): ?>
            <a href="/app/controllers/EtudiantController.php?action=dashboard">📊 Tableau de bord</a>
            <a href="/app/controllers/EtudiantController.php?action=deposer">📄 Déposer mon mémoire</a>
            <a href="/app/controllers/EtudiantController.php?action=planning">📅 Planning des soutenances</a>

        <?php elseif ($role === 'encadreur'): ?>
            <a href="/app/controllers/EncadreurController.php?action=dashboard">📊 Tableau de bord</a>
            <a href="/app/controllers/EncadreurController.php?action=memoires">📄 Mémoires à vérifier</a>

        <?php elseif ($role === 'membre_jury'): ?>
            <a href="/app/controllers/JuryController.php?action=dashboard">📊 Tableau de bord</a>
            <a href="/app/controllers/JuryController.php?action=planning">📅 Planning des soutenances</a>
            <a href="/app/controllers/JuryController.php?action=evaluer">✏️ Evaluer un étudiant</a>
            <a href="/app/controllers/JuryController.php?action=deliberer">⚖️ Délibération</a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-bottom">
        <div class="sidebar-section" style="color:#4a5f75;padding:14px 12px 6px;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;">Compte</div>
        <a href="/app/controllers/AuthController.php?action=logout">🚪 Se déconnecter</a>
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