<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Tableau de bord — Encadreur</h1>
    <span>Bonjour, <strong><?= htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom']) ?></strong></span>
</div>

<div class="cards">
    <div class="card">
        <h3><?= count($memoires) ?></h3>
        <p>Mémoires assignés</p>
    </div>
    <div class="card">
        <h3><?= count(array_filter($memoires, fn($m) => $m['statut'] === 'valide')) ?></h3>
        <p>Mémoires validés</p>
    </div>
    <div class="card">
        <h3><?= count(array_filter($memoires, fn($m) => $m['statut'] === 'en_attente')) ?></h3>
        <p>En attente</p>
    </div>
</div>

<div class="actions-bar">
    <a href="/app/controllers/EncadreurController.php?action=memoires" class="btn"><i class="fa-regular fa-folder-open" aria-hidden="true"></i> Voir les mémoires</a>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
