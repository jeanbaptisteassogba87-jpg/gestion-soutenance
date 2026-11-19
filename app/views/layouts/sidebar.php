<?php
$role = $_SESSION['role'] ?? '';
$dashboardLink = '';
switch ($role) {
    case 'administrateur': $dashboardLink = BASE_URL . '/app/controllers/AdminController.php?action=dashboard'; break;
    case 'etudiant': $dashboardLink = BASE_URL . '/app/controllers/EtudiantController.php?action=dashboard'; break;
    case 'encadreur': $dashboardLink = BASE_URL . '/app/controllers/EncadreurController.php?action=dashboard'; break;
    case 'membre_jury': $dashboardLink = BASE_URL . '/app/controllers/JuryController.php?action=dashboard'; break;
    default: $dashboardLink = BASE_URL . '/app/views/auth/login.php';
}
?>
<ul class="nav flex-column">
    <li class="nav-item mb-2">
        <a class="nav-link text-dark" href="<?= $dashboardLink ?>">
            <i class="fas fa-tachometer-alt"></i> Tableau de bord
        </a>
    </li>
    <?php if ($role === 'administrateur'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/AdminController.php?action=planifier">
                <i class="fas fa-calendar-plus"></i> Planifier soutenance
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/AdminController.php?action=affecter_jury">
                <i class="fas fa-users"></i> Affecter jury
            </a>
        </li>
    <?php elseif ($role === 'etudiant'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/EtudiantController.php?action=deposer">
                <i class="fas fa-upload"></i> Déposer mémoire
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/EtudiantController.php?action=planning">
                <i class="fas fa-calendar-alt"></i> Planning soutenance
            </a>
        </li>
    <?php elseif ($role === 'encadreur'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/EncadreurController.php?action=memoires">
                <i class="fas fa-check-double"></i> Vérifier mémoires
            </a>
        </li>
    <?php elseif ($role === 'membre_jury'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/JuryController.php?action=planning">
                <i class="fas fa-calendar-alt"></i> Planning
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/JuryController.php?action=evaluer">
                <i class="fas fa-star"></i> Évaluer
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/controllers/JuryController.php?action=deliberer">
                <i class="fas fa-gavel"></i> Délibérer
            </a>
        </li>
    <?php endif; ?>
</ul>
