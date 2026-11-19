<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Tableau de bord — Jury</h1>
    <span>Bonjour, <strong><?= htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom']) ?></strong></span>
</div>

<div class="cards">
    <div class="card">
        <h3><?= count($soutenances) ?></h3>
        <p>Mes soutenances</p>
    </div>
    <div class="card">
        <h3><?= count(array_filter($soutenances, fn($s) => !is_null($s['note']))) ?></h3>
        <p>Soutenances notées</p>
    </div>
</div>

<div class="actions-bar">
    <a href="/app/controllers/JuryController.php?action=evaluer" class="btn"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Évaluer</a>
    <a href="/app/controllers/JuryController.php?action=deliberer" class="btn btn-outline"><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i> Délibérer</a>
    <a href="/app/controllers/JuryController.php?action=planning" class="btn btn-outline"><i class="fa-regular fa-calendar" aria-hidden="true"></i> Planning</a>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Mes soutenances</h2></div>
    <table>
        <thead>
            <tr><th>#</th><th>Date</th><th>Heure</th><th>Salle</th><th>Rôle</th><th>Note</th><th>Mention</th></tr>
        </thead>
        <tbody>
        <?php if (empty($soutenances)): ?>
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:20px">Aucune soutenance assignée</td></tr>
        <?php else: ?>
            <?php foreach ($soutenances as $s): ?>
            <tr>
                <td><?= $s['idSoutenance'] ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= $s['heure'] ?></td>
                <td><?= htmlspecialchars($s['salle']) ?></td>
                <td><?= ucfirst($s['role']) ?></td>
                <td><?= $s['note'] ?? '—' ?></td>
                <td><?= $s['mention'] ?? '—' ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
