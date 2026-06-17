<?php
$role = $_SESSION['role'] ?? '';
$dashboardLink = '';
switch ($role) {
    case 'administrateur': $dashboardLink = BASE_URL . '/app/views/admin/dashboard.php'; break;
    case 'etudiant': $dashboardLink = BASE_URL . '/app/views/etudiant/dashboard.php'; break;
    case 'encadreur': $dashboardLink = BASE_URL . '/app/views/encadreur/dashboard.php'; break;
    case 'membre_jury': $dashboardLink = BASE_URL . '/app/views/jury/dashboard.php'; break;
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
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/admin/planifier_soutenance.php">
                <i class="fas fa-calendar-plus"></i> Planifier soutenance
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/admin/affecter_jury.php">
                <i class="fas fa-users"></i> Affecter jury
            </a>
        </li>
    <?php elseif ($role === 'etudiant'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/etudiant/deposer_memoire.php">
                <i class="fas fa-upload"></i> Déposer mémoire
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/etudiant/consulter_planning.php">
                <i class="fas fa-calendar-alt"></i> Planning soutenance
            </a>
        </li>
    <?php elseif ($role === 'encadreur'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/encadreur/verifier_memoire.php">
                <i class="fas fa-check-double"></i> Vérifier mémoires
            </a>
        </li>
    <?php elseif ($role === 'membre_jury'): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/jury/consulter_planning.php">
                <i class="fas fa-calendar-alt"></i> Planning
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/jury/evaluer_etudiant.php">
                <i class="fas fa-star"></i> Évaluer
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/app/views/jury/deliberer.php">
                <i class="fas fa-gavel"></i> Délibérer
            </a>
        </li>
    <?php endif; ?>
</ul>