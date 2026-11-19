
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('BASE_URL')) require_once __DIR__ . '/../../../config/config.php';

if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: /app/views/auth/login.php');
    exit();
}

require_once __DIR__ . '/../../helpers/security.php';
$role = $_SESSION['role'];
$currentController = basename($_SERVER['PHP_SELF'] ?? '');
$currentAction = $_GET['action'] ?? 'dashboard';
$isActive = static function (string $controller, array $actions) use ($currentController, $currentAction): string {
    return $currentController === $controller && in_array($currentAction, $actions, true) ? 'active' : '';
};
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

<button class="hamburger" id="hamburger" type="button"
        aria-label="Ouvrir le menu" aria-controls="sidebar" aria-expanded="false">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="sidebar-overlay" id="overlay"></div>

<div class="wrapper">

<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <h2><i class="fa-solid fa-graduation-cap"></i> UATM GASA</h2>
        <p>Gestion des soutenances</p>
    </div>

    <nav>
        <div class="sidebar-section">Menu</div>

        <?php if ($role === 'administrateur'): ?>

            <a class="<?= $isActive('AdminController.php', ['dashboard']) ?>" href="/app/controllers/AdminController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a class="<?= $isActive('AdminController.php', ['planifier']) ?>" href="/app/controllers/AdminController.php?action=planifier">
                <i class="fa-solid fa-calendar-plus"></i> Planifier une soutenance
            </a>
            <a class="<?= $isActive('AdminController.php', ['affecter_jury']) ?>" href="/app/controllers/AdminController.php?action=affecter_jury">
                <i class="fa-solid fa-users"></i> Changer un jury
            </a>

            <div class="sidebar-section">Gestion</div>

            <a class="<?= (in_array($currentAction, ['utilisateurs', 'creer_utilisateur', 'modifier_utilisateur', 'supprimer_utilisateur'], true) && ($_GET['type'] ?? 'etudiant') === 'etudiant') ? 'active' : '' ?>" href="/app/controllers/AdminController.php?action=utilisateurs&type=etudiant">
                <i class="fa-solid fa-user-graduate"></i> Etudiants
            </a>
            <a class="<?= ($currentAction === 'utilisateurs' && ($_GET['type'] ?? '') === 'encadreur') ? 'active' : '' ?>" href="/app/controllers/AdminController.php?action=utilisateurs&type=encadreur">
                <i class="fa-solid fa-chalkboard-user"></i> Encadreurs
            </a>
            <a class="<?= ($currentAction === 'utilisateurs' && ($_GET['type'] ?? '') === 'membre_jury') ? 'active' : '' ?>" href="/app/controllers/AdminController.php?action=utilisateurs&type=membre_jury">
                <i class="fa-solid fa-scale-balanced"></i> Membres jury
            </a>

        <?php elseif ($role === 'etudiant'): ?>

            <a class="<?= $isActive('EtudiantController.php', ['dashboard']) ?>" href="/app/controllers/EtudiantController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a class="<?= $isActive('EtudiantController.php', ['deposer']) ?>" href="/app/controllers/EtudiantController.php?action=deposer">
                <i class="fa-solid fa-file-pdf"></i> Déposer mon mémoire
            </a>
            <a class="<?= $isActive('EtudiantController.php', ['planning']) ?>" href="/app/controllers/EtudiantController.php?action=planning">
                <i class="fa-solid fa-calendar-days"></i> Planning des soutenances
            </a>

        <?php elseif ($role === 'encadreur'): ?>

            <a class="<?= $isActive('EncadreurController.php', ['dashboard']) ?>" href="/app/controllers/EncadreurController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a class="<?= $isActive('EncadreurController.php', ['memoires', 'valider', 'rejeter']) ?>" href="/app/controllers/EncadreurController.php?action=memoires">
                <i class="fa-solid fa-file-circle-check"></i> Mémoires à vérifier
            </a>

        <?php elseif ($role === 'membre_jury'): ?>

            <a class="<?= $isActive('JuryController.php', ['dashboard']) ?>" href="/app/controllers/JuryController.php?action=dashboard">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
            <a class="<?= $isActive('JuryController.php', ['planning']) ?>" href="/app/controllers/JuryController.php?action=planning">
                <i class="fa-solid fa-calendar-days"></i> Planning des soutenances
            </a>
            <a class="<?= $isActive('JuryController.php', ['evaluer']) ?>" href="/app/controllers/JuryController.php?action=evaluer">
                <i class="fa-solid fa-pen-to-square"></i> Evaluer un étudiant
            </a>
            <a class="<?= $isActive('JuryController.php', ['deliberer']) ?>" href="/app/controllers/JuryController.php?action=deliberer">
                <i class="fa-solid fa-scale-balanced"></i> Délibération
            </a>

        <?php endif; ?>
    </nav>

    <div class="sidebar-bottom">

        <div class="sidebar-section">
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
