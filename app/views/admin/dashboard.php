<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Dashboard — Administrateur</h1>
    <span>Bonjour, <strong><?= htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom']) ?></strong></span>
</div>

<div class="cards">
    <div class="card">
        <h3><?= count($soutenances) ?></h3>
        <p>Soutenances planifiées</p>
    </div>
    <div class="card">
        <h3><?= count($memoires) ?></h3>
        <p>Mémoires en attente</p>
    </div>
</div>

<div class="actions-bar">
    <a href="/app/controllers/AdminController.php?action=planifier" class="btn">+ Planifier une soutenance</a>
    <a href="/app/controllers/AdminController.php?action=affecter_jury" class="btn btn-outline">👥 Changer un jury</a>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Soutenances planifiées</h2></div>
    <table>
        <thead>
            <tr><th>#</th><th>Date</th><th>Heure</th><th>Salle</th><th>Note</th><th>Mention</th></tr>
        </thead>
        <tbody>
        <?php if (empty($soutenances)): ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:20px">Aucune soutenance planifiée</td></tr>
        <?php else: ?>
            <?php foreach ($soutenances as $s): ?>
            <tr>
                <td><?= $s['idSoutenance'] ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= $s['heure'] ?></td>
                <td><?= htmlspecialchars($s['salle']) ?></td>
                <td><?= $s['note'] ?? '—' ?></td>
                <td><?= $s['mention'] ?? '—' ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Mémoires en attente de validation</h2></div>
    <table>
        <thead>
            <tr><th>#</th><th>Titre</th><th>Date dépôt</th><th>Statut</th></tr>
        </thead>
        <tbody>
        <?php if (empty($memoires)): ?>
            <tr><td colspan="4" style="text-align:center;color:#aaa;padding:20px">Aucun mémoire en attente</td></tr>
        <?php else: ?>
            <?php foreach ($memoires as $m): ?>
            <tr>
                <td><?= $m['idMemoire'] ?></td>
                <td><?= htmlspecialchars($m['titre']) ?></td>
                <td><?= $m['dateDepot'] ?></td>
                <td><span class="badge badge-en_attente">En attente</span></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>